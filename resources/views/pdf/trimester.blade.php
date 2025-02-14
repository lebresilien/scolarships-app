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
            width: 100px;
            border-radius: 50%;
        }
        .delegation {
            text-transform: uppercase;
            font-size: 13px
        }
        .mt {
            margin-top: 3px
        }
        #block_1 {
            display: flex;
            justify-content: center;
            column-gap: 25px;
        }
        #report_name {
            background-color: rgb(163 163 163);
            border: 1px solid black;
            padding: 0 25px;
            text-transform: uppercase;
        }
        #block_2 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid black
        }
        .flex {
            display: flex
        }
        .block_3 {
            display: flex; 
            justify-content: 
            space-between; 
            width: 220px
        }
        table {
            border-collapse: collapse; 
            width: 100%
        }
        th, td {
            border: 1px solid black
        }
        #table_header {
            background-color: rgb(163 163 163);
        }
        .text-align {
            text-align: center
        }
        .no-border {
            border: 0px;
            background-color: rgb(163 163 163);
        }
        .bold {
            font-weight: bold
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

                    <span class="delegation block"> {{ !$school->is_primary_school ? 'ministeres des enseignements secondaires' : 'ministeres de l\'education de base' }} </span>

                    <span class="delegation block">delegation regionnale du centre</span>

                    <span class="delegation block">delegation departementale du nyong et kelle</span>

                    <span class="delegation block mt" >{{ $school->name }}</span>

                    <span class="block">BP: {{ $school->postal_address }} - Tel: {{ $school->phone_number }}</span>

                    <span class="delegation block">immatriculation: {{ $school->postal_address }}</span>
                
                </div>

                <div id="logo">
                    <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}"/> 
                </div>

                <div id="en">

                    <span class="country_name block">republique du cameroun</span>

                    <span class="country_ block">Peace-Work-Fatherland</span>

                    <span class="country_star block">********</span>

                    <span class="delegation block">{{ !$school->is_primary_school ? 'ministry of secondary education' : 'ministry of basic education'  }}</span>

                    <span class="delegation block">regional delegation of centre</span>

                    <span class="delegation block">sub-division of nyong et kelle</span>

                    <span class="delegation block m-t">{{ $school->name }}</span>

                    <span class="block">BP: {{ $school->postal_address }} - Tel: {{ $school->phone_number }}</span>

                    <span class="delegation block">immatriculation: {{ $school->postal_address }}</span>

                </div>

            </div>

            <div id="block_1" class="mt">

                <div id="report_name">
                    <p>bulletin de note du {{ $seq->name }}</p>
                </div>

                <p>Année scolaire: {{ $seq->academic->name }}</p>
                
            </div>

            <div id="block_2">

                <div>

                    <div class="flex">
                        <div class="block_3">
                            <span>Matricule&ensp;</span>
                            <span>:&ensp;</span>
                        </div>
                        <span>{{ $record['matricule'] }}</span>
                    </div>

                    <div class="flex">
                        <div class="block_3">
                            <span>Nom(s) et Prénom(s)&ensp;</span>
                            <span>:&ensp;</span>
                        </div>
                        <span style="text-transform: uppercase">{{ $record['fname'] . ' ' . $record['lname'] }}</span>
                    </div>

                    <div class="flex">
                        <div class="block_3">
                            <span>Date et lieu de naissance&ensp;</span>
                            <span>:&ensp;</span>
                        </div>
                        <span>{{ $record['sexe'] == 0 ? 'Née'  : 'Né' }} le {{ $record['born_at'] . ' à ' . $record['born_place'] }}</span>
                    </div>

                    <div class="flex">
                        <div class="block_3">
                            <span>Classe&ensp;</span>
                            <span>:&ensp;</span>
                        </div>
                        <span>{{ $record['current_classroom']['name'] }}</span>
                    </div>

                    <div class="flex">
                        <div class="block_3">
                            <span>Effectif&ensp;</span>
                            <span>:&ensp;</span>
                        </div>
                        <span>{{ $record['current_classroom']['count_student'] }}</span>
                    </div>

                    <div class="flex">
                        <div class="block_3">
                            <span>Redoublant(e)&ensp;</span>
                            <span>:&ensp;</span>
                        </div>
                        <span>{{ $policy->state ? 'Oui' : 'Non' }}</span>
                    </div>

                    <div class="flex">
                        <div class="block_3">
                            <span>Adresse&ensp;</span>
                            <span>:&ensp;</span>
                        </div>
                        <span>{{ $record['quarter'] }}</span>
                    </div>

                </div>

                <div>
                    @if ($record->logo)
                        <img src="{{ asset('storage/' . $record->logo) }}" alt="logo" /> 
                    @endif 
                </div>

            </div>

            <div>
                
                @if(!$school->is_primary_school)

                    <table>
                        
                        <thead>
                            <tr id="table_header">
                                <th>Matières</th>
                                <th>Eva 1 sur 20</th>
                                <th>Eva 2 sur 20</th>
                                <th>Coeff</th>
                                <th>Total</th>
                                <th>Moy sur 20</th>
                                <th>RG</th>
                                <th>Prem Dem</th>
                                <th>Enseignant</th>
                                <th>Observations</th>
                            </tr>
                        </thead>

                        <tbody>

                            @php

                                $semester_total_coefficient = 0;
                                $semester_total_pound = 0;
                                $semester_range = 0;

                            @endphp

                            @foreach($record['current_classroom']['group']['teachings'] as $teaching)

                                @if(count($teaching['courses']) > 0)

                                    @php

                                        $semester_total_coefficient += $teaching->courses->sum('coefficient');
                                        $ue_range = 1;
                                        $total_ue_notes = 0;

                                        $averages = $seq->notes($sequences_id)
                                                        ->selectRaw('classroom_student_id, (SUM(value) / '. $teaching->courses->sum('coefficient') . ') as average')
                                                        ->whereIn('course_id', $teaching->courses->pluck('id'))
                                                        ->groupBy('classroom_student_id')
                                                        ->get();

                                        $curent_student = $averages->where('classroom_student_id', $policy->id);
                                        
                                        foreach ($averages as $item) {
                                            if($item->average > $curent_student->value('average')) {
                                                $ue_range++;
                                            }
                                        }

                                    @endphp

                                    @foreach($teaching['courses'] as $course)

                                        <tr>
                                            <td style="text-transform: capitalize">
                                                {{ $course['name'] }}
                                            </td>
                                            <td class="text-align">
                                                {{ $policy->notes()->where('sequence_id', $sequences_id[0])->where('course_id', $course['id'])->first() ? $policy->notes()->where('sequence_id', $sequences_id[0])->where('course_id', $course['id'])->first()->value : 0 }}
                                            </td>
                                            <td class="text-align">
                                                {{ $policy->notes()->where('sequence_id', $sequences_id[1])->where('course_id', $course['id'])->first() ? $policy->notes()->where('sequence_id', $sequences_id[1])->where('course_id', $course['id'])->first()->value : 0 }}
                                            </td>
                                            <td class="text-align">
                                                {{ $course->coefficient  }}
                                            </td>
                                            <td class="text-align">
                                                @php
                                                    $total_ue_notes += (($policy->notes()->where('sequence_id', $sequences_id[0])->where('course_id', $course['id'])->first()->value + $policy->notes()->where('sequence_id', $sequences_id[1])->where('course_id', $course['id'])->first()->value) / 2 ) * $course->coefficient;
                                                @endphp
                                                {{ (($policy->notes()->where('sequence_id', $sequences_id[0])->where('course_id', $course['id'])->first()->value + $policy->notes()->where('sequence_id', $sequences_id[1])->where('course_id', $course['id'])->first()->value) / 2 ) * $course->coefficient }}
                                            </td>
                                            <td class="text-align">
                                                {{ number_format(($policy->notes()->where('sequence_id', $sequences_id[0])->where('course_id', $course['id'])->first()->value + $policy->notes()->where('sequence_id', $sequences_id[1])->where('course_id', $course['id'])->first()->value) / 2, 2) }}
                                            </td>
                                            <td class="text-align">
                                                @php
                                                    $position = 1;
                                                    $val = ($policy->notes()->where('sequence_id', $sequences_id[0])->where('course_id', $course['id'])->first()->value + $policy->notes()->where('sequence_id', $sequences_id[1])->where('course_id', $course['id'])->first()->value) / 2;
                                                    $course_notes = $course->notes()->selectRaw('classroom_student_id, (SUM(value) / 2) as value')->whereIn('sequence_id', $sequences_id)->where('classroom_student_id', '<>', $policy->id)->groupBy('classroom_student_id')->get();
                                                    foreach ($course_notes as $item) {
                                                        if($item->value > $val) {
                                                            $position++;
                                                        }
                                                    }
                                                    echo $position;
                                                @endphp
                                            </td>
                                            <td class="text-align">
                                                @php
                                                    $notes = $course->notes()->selectRaw('classroom_student_id, (SUM(value) / 2) as value')->whereIn('sequence_id', $sequences_id)->groupBy('classroom_student_id')->get();
                                                    echo 'P: ' . $notes->max('value') . '<br>';
                                                    echo 'D: ' . $notes->min('value');
                                                @endphp
                                            </td>
                                            <td class="text-align" style="text-transform: uppercase">
                                                {{ $course->teacher->name }}
                                            </td>
                                            <td class="text-align">
                                                @if($val < 10 )
                                                    Compétences non acquises
                                                @elseif ($val >= 10 && $val < 12)
                                                    Compétences moyennement acquises
                                                @elseif ($val >= 12 && $val < 15)
                                                    Compétences acquises
                                                @else
                                                    Compétences acquises
                                                @endif
                                            </td>
                                        </tr>

                                    @endforeach

                                    <tr>
                                        <td colspan="2" class="no-border" style="text-transform: capitalize; border-left: 1px solid black; padding-top: 7px; padding-bottom: 7px">
                                            {{ $teaching['name'] }}
                                        </td>
                                        <td class="text-align no-border">Total:</td>
                                        <td class="text-align no-border">
                                            {{ $teaching->courses->sum('coefficient') }}
                                        </td>
                                        <td class="no-border text-align">
                                            {{ $total_ue_notes }}
                                        </td>
                                        <td colspan="3" class="no-border"></td>
                                        <td class="no-border" style="text-align: right">Moyenne:</td>
                                        <td class="no-border" style="border-right: 1px solid black">
                                            <div style="display: flex; justify-content: space-around">
                                                <span>
                                                    {{ number_format(($total_ue_notes / $teaching->courses->sum('coefficient')), 2)}}
                                                </span>
                                                <span>Rang: </span>
                                                <span>{{ $ue_range }}/{{ $record['current_classroom']['count_student'] }}</span>
                                            </div>
                                        </td>
                                    </tr>

                                @endif

                                @php
                                    $semester_total_pound += $total_ue_notes; 
                                @endphp

                            @endforeach

                            <tr>
                                <td colspan="10" style="padding-top: 7px; padding-bottom: 7px; border-top: 2px solid black; background-color: rgb(163 163 163)">

                                    <div style="display: flex; justify-content: space-between">
                                        <span>RESULTATS TRIMESTRIELS</span>
                                        <span>Total: &nbsp;&nbsp;&nbsp; {{ $semester_total_pound }}</span>
                                        <span>Coeff: &nbsp;&nbsp;&nbsp; {{ $semester_total_coefficient }}</span>
                                        <span>Moyenne: &nbsp;&nbsp; {{ number_format($semester_total_pound/$semester_total_coefficient, 2) }}</span>
                                        <span>Rang: &nbsp;&nbsp;&nbsp; {{ $range }}</span>
                                    </div>

                                </td>
                            </tr>

                        </tbody>

                    </table>

                @endif

            </div>

            <div style="display: flex; column-gap: 3px; margin-top: 50px">

                <div style="width: 50%">
                    <table style="border-collapse: collapse;border: 1px solid black; width: 100%">
                        <tbody>
                            <tr>
                                <td style="background-color: rgb(163 163 163); text-align: center" colspan="8">
                                    Décisions du conseil de classe
                                </td>
                            </tr>
                            <tr style="background-color: rgb(163 163 163)">
                                <td class="text-align" colspan="3">DISCIPLINE</td>
                                <td class="text-align" colspan="4">TRAVAIL</td>
                            </tr>
                            <tr>
                                <td rowspan="2" style="border: 1px solid black">Absences</td>
                                <td class="bold">J</td>
                                <td class="text-align bold"><span style="display: block; padding: 10px"></span></td>
                                <td class="text-align bold">TB</td>
                                <td class="text-align bold">B</td>
                                <td class="text-align bold">AB</td>
                                <td class="text-align bold">PASS</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; font-weight: bold">NJ</td>
                                <td class="text-align bold"><span style="display: block; padding: 10px"></span></td>
                                <td class="text-align bold">MED</td>
                                <td class="text-align bold">INS</td>
                                <td class="text-align bold">FAIB</td>
                                <td class="text-align bold">NUL</td>
                            </tr>
                            <tr>
                                <td colspan="2">Avertissement</td>
                                <td></td>
                                <td rowspan="3" colspan="4">
                                    <div style="display: flex: flex-direction: column; width: 100%; height: 100%; line-height: 0.1">
                                        <p style="text-align: end; font-size: 15px;  margin-right: 10px">Un effort s'impose en</p>
                                        <p style="border-bottom: 1px solid black; margin-left: 10px; margin-right: 10px"></p>
                                        <p style="border-bottom: 1px solid black; margin-left: 10px; margin-right: 10px"></p>
                                        <p style="border-bottom: 1px solid black; margin-left: 10px; margin-right: 10px"></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">Blâmes de conduite</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2">Exclusions (en jours)</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="text-align: center; background-color: rgb(163 163 163)" colspan="3">Visa des parents ou tuteurs</td>
                                <td style="text-align: center; background-color: rgb(163 163 163)" colspan="4">Observation et visa du professeur titulaire</td>
                            </tr>
                            <tr>
                                <td style="height: 70px" colspan="3"></td>
                                <td style="height: 70px" colspan="4"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div style="width: 50%; border: 1px solid black">
                   
                   @php
                        $average_tri = number_format($semester_total_pound/$semester_total_coefficient, 2);
                    @endphp

                    <div style="display: flex; background-color: rgb(163 163 163)">
                        <div style="display: flex; flex-direction: column; width: 60%; border-right: 1px solid black; font-size: 14px; row-gap: 5px">
                            <span>Elève/Classe</span>
                            <div style="display: flex; justify-content: space-around">
                                <span>Rang</span>
                                <span>{{ $range }}/{{ $record['current_classroom']['count_student'] }}</span>
                                <span>Max</span>
                                <span>{{ round($statistics->max('average'), 2) }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-around">
                                <span>Moy</span>
                                <span>{{ $average_tri }}</span>
                                <span>Min</span>
                                <span>{{ round($statistics->min('average'), 2) }}</span>
                            </div>
                        </div>
                        <div style="display: flex; flex-direction: column; width: 40%">
                            <span style="font-size: 15px">MENTION</span>
                            {{-- <div style="display: flex; justify-content: center; text-transform: uppercase"></div> --}}
                            <div style="display: flex; border-top: 1px solid black; text-transform: uppercase; align-items: center; justify-content: center; height: 100%">

                                @if($average_tri < 10)
                                    <span class="text-align">Compétences non acquises</span>
                                @elseif ($average_tri >= 10 && $average_tri < 12)
                                    <span class="text-align">Compétences moyennement acquises</span>
                                @elseif ($average_tri >= 12 && $average_tri < 15)
                                    <span class="text-align">Compétences acquises</span>
                                @else
                                    <span class="text-align">Compétences bien acquises</span>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 57px">
                        <div style="display: flex; justify-content: center;">Visa du principal</div>
                        <div style="border-top: 1px solid black; height: 100px"></div>
                    </div>
                </div>

            </div>

        </div>

        <button onclick="imprimer('container')" style="margin-top: 10px; padding: 10px">Imprimer</button>

        <script>
            function imprimer(container){
                var printContents = document.getElementById(container).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>

    </body>
</html>