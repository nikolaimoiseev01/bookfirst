<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex">
            <input class="form-control" id="participants_input" type="text" placeholder="Поиск...">
            <button  style="width:fit-content; position: relative;"
                    class="add_promo_button ml-3 button btn btn-block bg-gradient-primary">
                <span class="button__text">Добавить новый</span>
            </button>
            </div>
        </div>
        <div class="card-body p-0">
            <style>
                td {
                    vertical-align: inherit !important;
                }
            </style>
            <table id="participants_table" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th scope="col" style="text-align: center;">id</th>
                    <th scope="col" style="text-align: center;">Промокод</th>
                    <th scope="col" style="text-align: center;">Процент скидки</th>
                    <th scope="col" style="text-align: center;">Создан</th>
                    <th scope="col" style="text-align: center;">Обновлен</th>
                    <th scope="col" style="text-align: center;">Действие</th>
                </tr>
                </thead>
                <tbody>
                {{App::setLocale('ru')}}
                <tr wire:ignore style="display: none; background: #9be4f554;" class="new_promo" >
                    <td scope="row" data-label="id" style="text-align: center;">
                        999
                    </td>
                    <td data-label="Промокод" style="width: 20%; text-align: center;">
                        <input wire:model="new_promocode" style="text-align: center" class="form-control" type="text">
                    </td>
                    <td data-label="Процент скидки" style="width: 20%; text-align: center;">
                        <input wire:model="new_discount" style="text-align: center" class="form-control" type="number">
                    </td>

                    <td></td> <td></td>
                    <td style="text-align: center">
                        <i wire:click.prevent="add_promocode()" style="font-size: 22px;" class="fas fa-save"></i>
                        <i style="font-size: 22px;" class="close_add_promo fas fa-times"></i>
                    </td>

                </tr>
                @foreach($promocodes as $promocode)

                    <tr wire:ignore>
                        <td scope="row" data-label="id" style="text-align: center;">
                            {{$promocode['id']}}
                        </td>
                        <td data-label="Промокод" style="width: 20%; text-align: center;">
                            <span  id="text_promo_{{$promocode['id']}}">{{$promocode['promocode']}}</span>
                            <input wire:model="promocode.{{$promocode['id']}}" style="display: none; text-align: center" value="{{$promocode['promocode']}}" class="form-control" id="input_promo_{{$promocode['id']}}" type="text">
                        </td>
                        <td data-label="Процент скидки" style="width: 20%; text-align: center;">
                            <span id="text_disc_{{$promocode['id']}}">{{round($promocode['discount'])}}%</span>
                            <input wire:model="discount.{{$promocode['id']}}" style="display: none; text-align: center" value="{{round($promocode['discount'])}}" class="form-control" id="input_disc_{{$promocode['id']}}" type="number">
                        </td>
                        <td data-label="Создана" style="text-align: center;">
                            {{ Date::parse($promocode['created_at'])->addHours(3)->format('j F, G:i') }}
                        </td>
                        <td data-label="Обновлена" style="text-align: center;">
                            {{ Date::parse($promocode['updated_at'])->addHours(3)->format('j F, G:i') }}
                        </td>
                       <td data-label="Обновлена" style="width: 1%; text-align: center;">
                           <i id="{{$promocode['id']}}" style="font-size: 22px;" class="edit_promo_button fas fa-edit"></i>
                           <div class="when_editing_icons" style="display:none" id="when_editing_icons_{{$promocode['id']}}">
                               <i wire:click.prevent="save_promocode({{$promocode['id']}})" id="save_{{$promocode['id']}}" style="font-size: 22px;" class="fas fa-save"></i>
                               <i id="stop_{{$promocode['id']}}" style="font-size: 22px;" class="stop_edit_promo_button fas fa-times"></i>
                           </div>
                        </td>

                    </tr>


                @endforeach
                </tbody>
            </table>



        </div>

        <!-- /.card-body -->

        <script>
            $('.add_promo_button').on('click', function() {
                $('.new_promo').show();
            })

            $('.close_add_promo').on('click', function() {
                $('.new_promo').hide();
            })


            $('.edit_promo_button').on('click', function() {
                var id = $(this).attr('id');
                $(this).hide();
                $('#when_editing_icons_' + id).show();
                $('#text_promo_' + id).hide();
                $('#text_disc_' + id).hide();
                $('#input_promo_' + id).show();
                $('#input_disc_' + id).show();
            })

            $('.stop_edit_promo_button').on('click', function() {
                var id = $(this).attr('id').split('_').pop();

                $('#when_editing_icons_' + id).hide();

                $('#' + id).show();
                $('#text_promo_' + id).show();
                $('#text_disc_' + id).show();
                $('#input_promo_' + id).hide();
                $('#input_disc_' + id).hide();


            })
        </script>

        <script>
            $(document).ready(function () {
                $("#participants_input").on("keyup", function () {
                    var value = $(this).val().toLowerCase();

                    $("#participants_table tbody tr").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>


    </div>
</div>
