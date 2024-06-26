<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Cv;


class CvController extends Controller
{
    public function createCV()
    {
        $user = Auth::user();
        $cv = new Cv();
        $cv->candidate_id = $user->id;
        $cv->save();
        return response()->json(
            ['message' => 'CV created successfully'],
            200
        );
    }
    public function deleteCV(Cv $cv)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        $cv->delete();
        return response()->json([
            'message' => 'Cv successfully deleted',
        ], 200);
    }
    public function getCVByCandidate()
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();

        if (!$cv) {
            return response()->json(['message' => 'CV not found for the authenticate candidate'], 404);
        }
        return response()->json($cv);
    }

    // CV FILE CRUD 
    public function uploadCV(Request $request)
    {

        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();

        $request->validate([
            'cv_file' => 'required|mimes:pdf|max:2048', // PDF files only, max 2MB
        ]);

        if ($request->file('cv_file')->isValid()) {
            $cvFileName = time() . '_' . $request->file('cv_file')->getClientOriginalName();

            // Store the file in the storage/app/public directory
            $request->file('cv_file')->storeAs('public/cv', $cvFileName);

            // Save the file path to the database
            $cv = new Cv();
            $cv->candidate_id = $user->id;
            $cv->cv_path = 'storage/cv/' . $cvFileName;
            $cv->save();

            return response()->json([
                'message' => 'CV uploaded successfully',
                'cv_path' => $cv->cv_path,
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid CV file',
            ], 400);
        }
    }

    public function updateCVPath(Request $request, CV $cv)
    {

        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();

        if (!$cv) {
            return response()->json([
                'message' => 'CV not found'
            ], 404);
        }
        if ($cv->candidate_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'cv_file' => 'required|mimes:pdf|max:2048', // PDF files only, max 2MB
        ]);

        if ($request->file('cv_file')->isValid()) {
            // Delete tha old CV File if exists
            if ($cv->cv_path) {
                Storage::delete($cv->cv_path);
            }

            $cvFileName = time() . '_' . $request->file('cv_file')->getClientOriginalName();

            // Store the new file into storage/app/public/cv
            $request->file('cv_file')->storeAs('public/cv', $cvFileName);

            // Update the path in DB
            $cv->cv_path = 'storage/cv/' . $cvFileName;
            $cv->save();

            return response()->json([
                'message' => 'CV updated successfully',
                'cv_path' => $cv->cv_path,
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid CV file',
            ], 400);
        }
    }

    public function deleteCVPath(Cv $cv)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();

        if (!$cv) {
            return response()->json([
                'message' => 'CV not found'
            ], 404);
        }

        if ($cv->cv_path) {
            Storage::delete($cv->cv_path);
        }

        $cv->cv_path = null;
        $cv->save();

        return response()->json([
            'message' => 'CV path deleted successfully',
        ]);
    }

    // Education CRUD
    public function createEducation(Request $request)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        if ($cv) {
            $education = $request->input('education');
            $educations = $cv->education;
            $educations[] = $education;
            $cv->education = $educations;
            $cv->save();
            return response()->json(['message' => 'Education created successfully'], 200);
        } else {
            return response()->json(['message' => ' Cv not found'], 404);
        }
    }

    public function updateEducation(Request $request, Cv $cv, $index)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();

        $education = $request->input('education');
        $educations = $cv->education;
        if (isset($educations[$index])) {
            $educations[$index] = $education;
            $cv->education = $educations;
            $cv->save();
            return response()->json(['message' => 'Education updated successfully'], 200);
        }
        return response()->json(['message' => 'Education not found'], 404);
    }

    public function deleteEducation(Cv $cv, $index)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        $educations = $cv->education;
        if (isset($educations[$index])) {
            unset($educations[$index]);
            $cv->education = array_values($educations); // Reset array keys
            $cv->save();
            return response()->json(['message' => 'Education deleted successfully'], 200);
        }
        return response()->json(['message' => 'Education not found'], 404);
    }

    public function getEducations(Cv $cv)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        return response()->json($cv->education);
    }

    // Skills CRUD
    public function createSkill(Request $request, Cv $cv)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        if ($cv) {
            $skill = $request->input('skill');
            $skills = $cv->skills;
            $skills[] = $skill;
            $cv->skills = $skills;
            $cv->save();
            return response()->json(['message' => 'Skill created successfully'], 200);
        } else {
            return response()->json(['message' => ' Cv not found'], 404);
        }
    }

    public function updateSkill(Request $request, Cv $cv, $index)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        $skill = $request->input('skill');
        $skills = $cv->skills;
        if (isset($skills[$index])) {
            $skills[$index] = $skill;
            $cv->skills = $skills;
            $cv->save();
            return response()->json(['message' => 'Skill updated successfully']);
        }
        return response()->json(['message' => 'Skill not found'], 404);
    }
    public function deleteSkill(Cv $cv, $index)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        $skills = $cv->skills;
        if (isset($skills[$index])) {
            unset($skills[$index]);
            $cv->skills = array_values($skills); // Reset array keys
            $cv->save();
            return response()->json(['message' => 'Skill deleted successfully']);
        }
        return response()->json(['message' => 'Skill not found'], 404);
    }

    public function getSkills(Cv $cv)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        return response()->json($cv->skills);
    }

    // Certifications CRUD
    public function createCertification(Request $request, Cv $cv)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        if ($cv) {
            $certification = $request->input('certification');
            $certifications = $cv->certifications;
            $certifications[] = $certification;
            $cv->certifications = $certifications;
            $cv->save();
            return response()->json(['message' => 'Certification created successfully'], 200);
        } else {
            return response()->json(['message' => ' Cv not found'], 404);
        }

        return response()->json(['message' => 'Certification created successfully'], 201);
    }

    public function updateCertification(Request $request, Cv $cv, $index)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        $certification = $request->input('certification');
        $certifications = $cv->certifications;
        if (isset($certifications[$index])) {
            $certifications[$index] = $certification;
            $cv->certifications = $certifications;
            $cv->save();
            return response()->json(['message' => 'Certification updated successfully']);
        }
        return response()->json(['message' => 'Certification not found'], 404);
    }

    public function deleteCertification(Cv $cv, $index)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        $certifications = $cv->certifications;
        if (isset($certifications[$index])) {
            unset($certifications[$index]);
            $cv->certifications = array_values($certifications);
            $cv->save();
            return response()->json(['message' => 'Certification deleted successfully']);
        }
        return response()->json(['message' => 'Certification not found'], 404);
    }

    public function getCertifications(Cv $cv)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        return response()->json($cv->certifications);
    }

    // languages CRUD
    public function createLanguage(Request $request, Cv $cv)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        $language = $request->input('language');
        $languages = $cv->languages;
        $languages[] = $language;
        $cv->languages = $languages;
        $cv->save();

        return response()->json(['message' => 'Language created successfully'], 200);
    }

    public function updateLanguage(Request $request, Cv $cv, $index)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        $language = $request->input('language');
        $languages = $cv->languages;
        if (isset($languages[$index])) {
            $languages[$index] = $language;
            $cv->languages = $languages;
            $cv->save();
            return response()->json(['message' => 'Language updated successfully']);
        }
        return response()->json(['message' => 'Language not found'], 404);
    }

    public function deleteLanguage(Cv $cv, $index)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        $languages = $cv->languages;
        if (isset($languages[$index])) {
            unset($languages[$index]);
            $cv->languages = array_values($languages);
            $cv->save();
            return response()->json(['message' => 'Language deleted successfully']);
        }
        return response()->json(['message' => 'Language not found'], 404);
    }
    public function getLanguages(Cv $cv)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        return response()->json($cv->languages);
    }
    
    // Experiences CRUD
    public function createExperience(Request $request, Cv $cv)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        $experience = $request->input('experience');
        $experiences = $cv->experiences;
        $experiences[] = $experience;
        $cv->experiences = $experiences;
        $cv->save();

        return response()->json(['message' => 'Experience created successfully'], 200);
    }

    public function updateExperience(Request $request, Cv $cv, $index)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        $experience = $request->input('experience');
        $experiences = $cv->experiences;
        if (isset($experiences[$index])) {
            $experiences[$index] = $experience;
            $cv->experiences = $experiences;
            $cv->save();
            return response()->json(['message' => 'Experience updated successfully']);
        }
        return response()->json(['message' => 'Experience not found'], 404);
    }

    public function deleteExperience(Cv $cv, $index)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        $experiences = $cv->experiences;
        if (isset($experiences[$index])) {
            unset($experiences[$index]);
            $cv->experiences = array_values($experiences);
            $cv->save();
            return response()->json(['message' => 'Experience deleted successfully']);
        }
        return response()->json(['message' => 'Experience not found'], 404);
    }

    public function getExperiences(Cv $cv)
    {
        $user = Auth::user();
        $cv = Cv::where('candidate_id', $user->id)->first();
        return response()->json($cv->experiences);
    }
}
