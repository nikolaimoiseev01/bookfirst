<x-ui.modal name="modalForeignPayment">
    <div
        class="flex flex-col gap-4 p-4"
    >
        <h3 class="text-2xl md:text-xl font-semibold text-dark-400">Иностранные средства оплаты</h3>
        <p>Оплата иностранными средствами производится в ручном режиме. Необходимо сделать перевод
            вручную на любой из реквизитов ниже. В том числе доступны банки Казахстана (предпочтительнее) и
            Венгрии. <br>
            Как только совершите платеж, необходимо прислать его подтверждение (квитанцию) в чат наверху текущей страницы.<br>
            Далее мы проверим платеж и переведем заявку в статус "подтвержденно".
        </p>
        <x-ui.link :navigate="false" href="/fixed/public_documents/rekviziti_pervaja_kniga.pdf" color="yellow" download="Реквизиты Первая Книга">Скачать реквизиты</x-ui.link>
    </div>
</x-ui.modal>
