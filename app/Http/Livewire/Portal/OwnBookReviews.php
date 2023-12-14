<?php

namespace App\Http\Livewire\Portal;

use App\Models\OwnBookReview;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OwnBookReviews extends Component
{
    public $own_book;
    public $reviews;
    public $stars;
    public $review_text = '';

    use WithPagination;

    public function render()
    {
        $this->reviews =OwnBookReview::where('own_book_id', $this->own_book['id'])->paginate(3);
        return view('livewire.portal.own-book-reviews', [
            'reviews' => $this->reviews
        ]);
    }

    public function mount($own_book)
    {

        $this->own_book = $own_book;
    }

    public function new_message()
    {

        if (Auth::user()->id ?? null) {

            $this->error_texts = [];

            if ($this->review_text == '') {
                array_push($this->error_texts, 'Введите сообщение');
            }
            if (!$this->stars) {
                array_push($this->error_texts, 'Выберите оценку');
            }

            if (!empty($this->error_texts)) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Что-то пошло не так!',
                    'text' => implode("<br>", $this->error_texts),
                ]);
            } else {

                OwnBookReview::create([
                    'user_id' => Auth::user()->id,
                    'own_book_id' => $this->own_book['id'],
                    'text' => $this->review_text,
                    'stars' => intval($this->stars)
                ]);

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Успешно!',
                    'text' => 'Отзыв оставлен.',
                ]);

                $this->review_text = '';
                $this->stars = null;
                $this->reviews = OwnBookReview::where('own_book_id', $this->own_book['id'])->get();
//                dd($this->reviews);
            }
        }

    }
}
