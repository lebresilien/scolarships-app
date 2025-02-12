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
                                                {{ $course['coefficient']  }}
                                            </td>
                                            <td class="text-align">
                                                @php
                                                    $total_ue_notes += $policy->notes()->where('sequence_id', $sequences_id[0])->where('course_id', $course['id'])->first()->value + $policy->notes()->where('sequence_id', $sequences_id[1])->where('course_id', $course['id'])->first()->value;
                                                @endphp
                                                {{ $policy->notes()->where('sequence_id', $sequences_id[0])->where('course_id', $course['id'])->first()->value + $policy->notes()->where('sequence_id', $sequences_id[1])->where('course_id', $course['id'])->first()->value }}
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
                                        <td colspan="2" class="no-border" style="padding-top: 10px; padding-bottom: 10px">
                                            {{ $teaching['name'] }}
                                           {{--  <div style="display: flex; justify-content: space-between">

                                                <span style="text-transform: uppercase">{{ $teaching['name'] }}</span>

                                            </div> --}}

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
                                        <td class="no-border">
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

                            @endforeach

                            <tr>

                            </tr>
                        </tbody>

                    </table>

                @endif

            </div>

        </div>

    </body>
</html>