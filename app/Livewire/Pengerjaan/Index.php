<?php

namespace App\Livewire\Pengerjaan;

use App\Models\Answer;
use App\Models\Statement;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Index extends Component
{
    #[Reactive]
    public $group_id;
    public $instrumens;
    public $questions = [];
    public $point = 0;
    public $currentQuestionIndex = 0;
    public $question = [];
    public $answer;
    public $jawaban_id;
    public $hasiPekerjaan = [];

    protected $listeners = ["changeQuestion"];

    public function changeQuestion($data)
    {
        $this->currentQuestionIndex = $data;
        $this->question = $this->questions[$this->currentQuestionIndex];
        $this->shareCurrentIndex();
        $this->shareCurentResult();
    }

    public function syncPengerjaan()
    {
        try {
            $this->calculationPoint();
            $user = auth()->user();
            $instrumen_id = $this->instrumens->id;
            $point = 0;


            foreach ($this->hasiPekerjaan as $key => $value) {
                $stm = Statement::findOrFail($key)->favorable;
                $jwb = Answer::findOrFail($value);
                if ($stm == "fav") {

                    $point = $point + $jwb->point_fav;
                } else {
                    $point = $point + $jwb->point_unfav;
                }
            }

            $criteria = $this->instrumens->criteria()
                ->where('point_min', '<=', $point)
                ->where('point_max', '>=', $point)
                ->first();
            $subCriterias = $this->instrumens->sub_criterias;
            $pointMax = Answer::orderBy("point_fav", "desc")->where("instrumen_id", $this->instrumens->id)->first();
            foreach ($subCriterias as $data) {
                $pointPerSubKriteria = 0;
                foreach ($data->statements as $stm) {
                    $jwb = Answer::findOrFail($this->hasiPekerjaan[$stm->id]);
                    if ($stm->favorable == "fav") {
                        $pointPerSubKriteria = $pointPerSubKriteria + $jwb->point_fav;
                    } else {
                        $pointPerSubKriteria = $pointPerSubKriteria + $jwb->point_unfav;
                    }
                }

                $persentase = 0.0;
                if ($pointPerSubKriteria != 0) {
                    $persentase = $pointPerSubKriteria / (count($data->statements) * $pointMax->point_fav);
                    $persentase *= 100;
                }

                $status = "";
                if ($persentase >= 75.0) {
                    $status = "tinggi";
                } else if ($persentase >= 40.0) {
                    $status = "sedang";
                } else {
                    $status = "rendah";
                }
                $tes = $user->nilaiTiapSubKriteria()->syncWithoutDetaching(
                    [
                        $data->id => [
                            "group_id" => $this->group_id,
                            "instrumen_id" => $instrumen_id,
                            "point" => $pointPerSubKriteria,
                            "pointMax" => (count($data->statements) * $pointMax->point_fav),
                            "status" => $status,
                        ]
                    ]
                );
            }

            $user->pengerjaanByInstrumen()->syncWithoutDetaching([
                $instrumen_id => [
                    'group_id' => $this->group_id,
                    'points' => $point,
                    'criteria_id' => $criteria->id
                ]
            ]);

            session()->flash("success", "Berhasil mengumpulkan jawaban");
            return $this->redirectRoute("user.dashboard");
        } catch (Exception $e) {
            Log::debug('Sync data:', [
                "message" => $e->getMessage(),
            ]);
            session()->flash("error", $e->getMessage());
        }
        // Dapatkan user yang sedang terautentikasi
    }

    public function mount()
    {
        $this->questions = $this->instrumens->statements;
        foreach ($this->questions as $i => &$question) {
            $question["point"] = 0;
            $question["no"] = $i + 1;
            $question["index"] = $i;
            $question["jawaban_id"] = null;
            unset($question["instrumen_id"]);
            unset($question["sub_kriteria_id"]);
            unset($question["created_at"]);
            unset($question["updated_at"]);
        }
        $this->question = $this->questions[$this->currentQuestionIndex];
        $this->answer = $this->instrumens->answer()->get();
        $this->jawaban_id = $this->answer->first()->id;
    }

    public function nextQuestion()
    {
        $this->calculationPoint();
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
            $this->question = $this->questions[$this->currentQuestionIndex];
        }
        $this->shareCurrentIndex();
    }

    public function prevQuestion()
    {
        $this->calculationPoint();
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
            $this->question = $this->questions[$this->currentQuestionIndex];
        }
        $this->shareCurrentIndex();
    }

    public function calculationPoint()
    {
        $data = $this->questions[$this->currentQuestionIndex];
        $this->hasiPekerjaan[$data->id] =  $this->jawaban_id;
    }

    public function shareCurrentIndex()
    {
        $this->shareCurentResult();
        $this->dispatch("current_index_question", $this->currentQuestionIndex);
    }

    public function shareCurentResult()
    {
        $this->dispatch("current_result", $this->hasiPekerjaan);
    }

    public function render()
    {
        return view('livewire.pengerjaan.index', [
            "question" => $this->question,
            "hasil" => $this->hasiPekerjaan,
            "opsi_answer" => $this->answer,
            "nomor" => $this->currentQuestionIndex,
            "totalSoal" => count($this->questions),
        ]);
    }
}
