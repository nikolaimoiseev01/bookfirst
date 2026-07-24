<div
    id="book"
    style="
        width: 380px;
        aspect-ratio: 148 / 210;
        position: relative;
        text-align: center;
        cursor: pointer;
    "
>
    <!-- Book Cover -->
    <div
        style="
            position: absolute;
            z-index: 1;
            width: 100%;
            height: 100%;
            border-radius: 3px;
            transform-origin: left;
            background-image: url('{{ $cover }}');
            background-size: 100% 100%;
            background-repeat: no-repeat;
        "
    >
        <div
            style="
                height: 100%;
                margin-left: 10px;
                width: 20px;
                border-left: 1px solid rgba(0, 0, 0, 0.07);
                background: linear-gradient(
                    to right,
                    rgba(255, 255, 255, 0.2),
                    transparent
                );
            "
        ></div>

        <div
            style="
                position: absolute;
                top: 0;
                height: 100%;
                width: 10px;
                margin-right: 10px;
                border-right: 1px solid rgba(0, 0, 0, 0.07);
                background: linear-gradient(
                    to right,
                    rgba(255, 255, 255, 0.2),
                    transparent
                );
            "
        ></div>

        <!-- Light -->
        <div
            style="
                position: absolute;
                top: 0;
                right: 0;
                height: 100%;
                width: 90%;
                border-radius: 3px;
                opacity: 0.1;
                background: linear-gradient(
                    to right,
                    transparent,
                    rgba(255, 255, 255, 0.2)
                );
            "
        ></div>
    </div>

    <!-- Book Inside -->
    <div
        style="
            position: relative;
            top: 2%;
            width: calc(100% - 2px);
            height: 96%;
            border-radius: 3px;
            border: 1px solid #9ca3af;
            background: white;
            box-shadow:
                2px 3px 8px 6px rgba(0, 0, 0, 0.14),
                inset -2px 0 0 gray,
                inset -3px 0 0 #dbdbdb,
                inset -4px 0 0 white,
                inset -5px 0 0 #dbdbdb,
                inset -6px 0 0 white,
                inset -7px 0 0 #dbdbdb,
                inset -8px 0 0 white,
                inset -9px 0 0 #dbdbdb;
        "
    ></div>
</div>
