<html>
    <head>
        <title>Bulletin</title>
        <style>
            .watermark {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 50px;
                color: rgba(128, 128, 128, 0.2);
                z-index: -1;
            }
            #container {
                display: flex;
                flex-direction: column;
                row-gap: 10px
            }
            #report-header {
                display: flex;
                flex-direction: row;
                column-gap: 10px;
                justify-content: space-between;
                background: red;
                align-items: center
            }
            #fr {
                display: flex;
                flex-direction: column;
                row-gap: 10px;
                justify-content: center
            }
            #en {
                display: flex;
                flex-direction: column;
                row-gap: 10px;
                justify-content: center
            }
            .block {
                display: block
            }
            .country_name {
                text-transform: uppercase;
                font-weight: black;
            }
            .country_ {
                text-transform: capitalize;
                font-weight: 400;
                font-size: 14px
            }
            .country_start {
                font-weight: 400;
                font-size: 14px
            }
        </style>
    </head>
    <body>
        <div class="watermark">CONFIDENTIEL</div>

        <div id="container">

            <div id="report-header">

                <div id="fr">

                    <span class="country_name block">republique du cameroun</span>

                    <span class="country_ block">Paix-Travail-Patrie</span>

                    <span class="country_star block">********</span>
                    
                </div>

                <div id="logo"></div>

                <div id="en">

                    <span class="country_name block">republique du cameroun</span>

                    <span class="country_ block">Paix-Travail-Patrie</span>

                    <span class="country_star block">********</span>

                </div>

            </div>

        </div>

    </body>
</html>