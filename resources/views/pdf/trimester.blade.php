<html>
    <head>
        <title>Bulletin</title>
    </head>
    <style>
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 30px;
            color: rgba(128, 128, 128, 0.2);
            z-index: -1;
            text-transform: uppercase
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
            font-size: 15px
            /* align-items: center */
        }
        #fr {
            display: flex;
            flex-direction: column;
          
        }
        #en {
            display: flex;
            flex-direction: column;
        }
        .block {
            display: block;
            text-align: center
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
        img {
            height: 100px;
            width: 100px
        }
        .delegation {
            text-transform: uppercase;
            font-size: 13px
        }
        .m-t {
            margin-top: 3px
        }
    </style>
    <body>

        <div class="watermark">{{ $school->name }}</div>

        <div id="container">

            <div id="report-header">

                <div id="fr">

                    <span class="country_name block">republique du cameroun</span>

                    <span class="country_ block">Paix-Travail-Patrie</span>

                    <span class="country_star block">********</span>

                    <span class="delegation block">ministeres des enseignements secondaires</span>

                    <span class="delegation block">delegation regionnale du centre</span>

                    <span class="delegation block">delegation departementale du nyong et kelle</span>

                    <span class="delegation block m-t" >{{ $school->name }}</span>

                    <span class="delegation block">bp: {{ $school->postal_address }} - {{ $school->phone_number }}</span>

                    <span class="delegation block">immatriculation: {{ $school->postal_address }}</span>
                
                </div>

                <div id="logo">
                    <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}"/> 
                </div>

                <div id="en">

                    <span class="country_name block">republique du cameroun</span>

                    <span class="country_ block">Peace-Work-Fatherland</span>

                    <span class="country_star block">********</span>

                    <span class="delegation block">ministry of secondary education</span>

                    <span class="delegation block">regional delegation of centre</span>

                    <span class="delegation block">sub-division of nyong et kelle</span>

                    <span class="delegation block m-t">{{ $school->name }}</span>

                    <span class="delegation block">bp: {{ $school->postal_address }} - {{ $school->phone_number }}</span>

                    <span class="delegation block">immatriculation: {{ $school->postal_address }}</span>

                </div>

            </div>

        </div>

    </body>
</html>