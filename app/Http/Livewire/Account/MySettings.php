<?php

namespace App\Http\Livewire\Account;

use App\Models\Printorder;
use App\Models\User;
use App\Models\Work;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Image\Image;

class MySettings extends Component
{
    use WithFileUploads;

    Public $name;
    Public $surname;
    Public $nickname;
    Public $email;
    Public $password;
    Public $avatar;
    Public $show_input = false;
    Public $avatar_file;
    public $avatar_file_name;
    public $avatar_file_extension;
    Public $avatar_file_cropped;
    Public $avatar_file_preview;
    public $cropped_img = "";

    protected $listeners = ['save_avatar','refreshComponent' => '$refresh'];

    public function render()
    {
        if ($this->avatar_file ?? 0 != null & $this->avatar_file_preview == null) {
            if(file_exists(storage_path('app/livewire-tmp/' . $this->avatar_file->getfilename()))) {
                $file_old_temp_path = storage_path('app/livewire-tmp/' . $this->avatar_file->getfilename());
                $file_new_temp_path = public_path('filepond_temp/user_avatars_temp/' . $this->avatar_file->getfilename());
                $this->avatar_file_name = $this->avatar_file->getfilename();
                $this->avatar_file_extension = $this->avatar_file->extension();
                File::move($file_old_temp_path, $file_new_temp_path); // перемещаем в нашу временную папку
                $this->avatar_file_preview = '/filepond_temp/user_avatars_temp/' . $this->avatar_file->getfilename();
                $this->dispatchBrowserEvent('update_preview');
            }
        } else {
            $avatar_file_preview = "";
        }
        return view('livewire.account.my-settings',[
            'avatar_file_preview' => $this->avatar_file_preview,
            'name' => $this->name,
            'surname' => $this->surname,
            'nickname' => $this->nickname,
            'email' => $this->email,
            'password' => $this->password,
            'avatar' => $this->avatar
        ]);
    }

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->surname = Auth::user()->surname;
        $this->nickname = Auth::user()->nickname;
        $this->email = Auth::user()->email;
        $this->avatar = Auth::user()->avatar;
        $this->avatar_cropped = Auth::user()->avatar_cropped;
        $this->password = Auth::user()->password;
    }

    public function save_avatar() {
        // Если есть изображение: оптимизируем его и уменьшаем

        // Большая картинка
        if ($this->avatar_file ?? 0 != null) {
            $file_old_temp_path = public_path('filepond_temp/user_avatars_temp/' . $this->avatar_file_name);
            $cur_width = Image::load($file_old_temp_path)->getWidth();
            if ($cur_width > 350) {
                Image::load($file_old_temp_path)
                    ->width(350)
                    ->optimize()
                    ->save($file_old_temp_path);
            }
            $file_new_path = public_path('img/avatars/avatar_user_' . Auth::user()->id . '.' . $this->avatar_file_extension);

            File::move($file_old_temp_path, $file_new_path); // перемещаем в нашу временную папку
            $picture = '/img/avatars/avatar_user_' . Auth::user()->id . '.' . $this->avatar_file_extension;
        } else {
            $picture = null;
        }


        if ($this->avatar_file ?? 0 != null) {
            // Обрезанная картинка
            $folderPath_cropped = public_path('img/avatars/');
            $image_parts_cropped = explode(";base64,", $this->cropped_img);
            $image_type_aux_cropped = explode("image/", $image_parts_cropped[0]);
            $image_type_cropped = $image_type_aux_cropped[1];
            $image_base64 = base64_decode($image_parts_cropped[1]);
//        $file_cropped = $folderPath_cropped . uniqid() . '.png';
            $filename_cropped = 'avatar_user_' . Auth::user()->id  . '_cropped.' . $image_type_cropped;
            $file_cropped = $folderPath_cropped . $filename_cropped;
            file_put_contents($file_cropped, $image_base64);

            $cur_width = Image::load($file_cropped)->getWidth();
            if ($cur_width > 350) {
                Image::load($file_cropped)
                    ->width(350)
                    ->optimize()
                    ->save($file_cropped);
            }
            $picture_cropped = '/img/avatars/avatar_user_' . Auth::user()->id . '_cropped.'. $image_type_cropped;
        }

        // ------------------------------------------------------------------------------



        User::where('id', Auth::user()->id)->update([
            'avatar' => $picture,
            'avatar_cropped' => $picture_cropped
        ]);

        session()->flash('show_modal', 'yes');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Аватар успешно изменен!');
        session()->flash('alert_text', '');
        return redirect(request()->header('Referer'));



    }

    public function save()
    {
        if(
            $this->name === ''
            OR $this->surname === ''
            OR $this->nickname === ''
            OR $this->email === ''
            OR $this->password === ''
        )
        {
            $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'error',
            'title' => 'Что-то пошло не так',
            'text' => 'Ни одно поле не должно быть пустым!',
        ]);}
        else
        {
            // ---- Редактируем Заказ печатных! ---- //
            User::where('id', Auth::user()->id)->update([
                'name' => $this->name,
                'surname' => $this->surname,
                'nickname' => $this->nickname,
                'email' => $this->email,
            ]);
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Информация успешно обновлена!',
                'text' => '']);

// ----------------------------------------------------------- //
            $this->show_input = 0;
        }
    }
}
