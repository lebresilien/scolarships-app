<div style="display: flex; flex-direction: column; row-gap: 10px; padding-left: 10px; padding-right: 10px; font-weight: 700;">
    
    <div style="display:flex; flex-direction:row; justify-content: space-between; padding-top: 5px; padding-bottom: 5px; border-top: 1px solid black; border-bottom: 1px solid black">
        
        <div style="display: flex; align-items: center; column-gap: 50px">

            <div style="">Logo Here</div>

            <div style="font-size: 14px; line-height: 18px">

                <span style="display: block">school name</span>

                <span style="display: block">Location</span>

                <span style="display: block">Tel:678660800/694282821</span>

                <span style="display: block">Enseignement secondaire</span>

            </div>

        </div>

        <div style="display: flex; flex-direction: column; align-items: center">

            <span style="display: block; font-weight: 800; text-align: right">République du Cameroun</span>
            
            <span style="display: block; font-weight: 800; text-align: right">Republic of Cameroon</span>
            
            <div style="display: flex; line-height: 1px; flex-direction: column; align-items: center; center; text-align: center; padding-right: 45px; padding-left: 45px; background-color: rgb(163 163 163)">
                
                <p style="text-transform: uppercase">bulletin de notes du {{ $seq->name }}</p> 

                <p style="font-size: 12px; ">Année scolaire: {{ $seq->academic->name }}</p>

            </div>

        </div>
        
    </div>

    <div style="font-size: 15px">

        <div style="display: flex">
            <div style="display: flex; justify-content: space-between; width: 220px">
                <span>Matricule&ensp;</span>
                <span>:&ensp;</span>
            </div>
            <span>{{ $record['matricule'] }}</span>
        </div>

        <div style="display: flex">
            <div style="display: flex; justify-content: space-between; width: 220px">
                <span>Nom(s) et Prénom(s)&ensp;</span>
                <span>:&ensp;</span>
            </div>
            <span style="text-transform: uppercase">{{ $record['fname'] . ' ' . $record['lname'] }}</span>
        </div>

        <div style="display: flex">
            <div style="display: flex; justify-content: space-between; width: 220px">
                <span>Date et lieu de naissance&ensp;</span>
                <span>:&ensp;</span>
            </div>
            <span>Né le {{ $record['born_at'] . ' à ' . $record['born_place'] }}</span>
        </div>

        <div style="display: flex">
            <div style="display: flex; justify-content: space-between; width: 220px">
                <span>Classe&ensp;</span>
                <span>:&ensp;</span>
            </div>
            <span>{{ $record['current_classroom']['name'] }}</span>
        </div>

        <div style="display: flex">
            <div style="display: flex; justify-content: space-between; width: 220px">
                <span>Effectif&ensp;</span>
                <span>:&ensp;</span>
            </div>
            <span>{{ $record['current_classroom']['count_student'] }}</span>
        </div>

        <div style="display: flex">
            <div style="display: flex; justify-content: space-between; width: 220px">
                <span>Classe redoublée&ensp;</span>
                <span>:&ensp;</span>
            </div>
            <span>{{ $policy->state ? 'Oui' : 'Non' }}</span>
        </div>

        <div style="display: flex">
            <div style="display: flex; justify-content: space-between; width: 220px">
                <span>Adresse&ensp;</span>
                <span>:&ensp;</span>
            </div>
            <span>{{ $record['quarter'] }}</span>
        </div>

    </div>

    <div>
        
        <table style="border-collapse: collapse; width: 100%">
                
            <thead>
                <tr>
                    <th style="border: 1px solid black">Matières</th>
                    <th style="border: 1px solid black">Note</th>
                    <th style="border: 1px solid black">Coeff</th>
                    <th style="border: 1px solid black">Total</th>
                    <th style="border: 1px solid black">Rang</th>
                    <th style="border: 1px solid black">Mention</th>
                </tr>
            </thead>
        
            <tbody>
                @php
                    $semester_total_coefficient = 0;
                    $semester_total_pound = 0;
                    $semester_range = 0;
                @endphp
                @foreach($record['current_classroom']['group']['teachings'] as $teaching)
                    @if(count($teaching['courses']) > 0 )
                        @php
                            $total_teaching = 0;
                            $total_coefficient = 0;
                            $policies = $seq->notes->where('classroom_student_id', '<>', $record->policy)->whereIn('course_id', $teaching['courses']->pluck('id'))->pluck('classroom_student_id');
                            $notes = $seq->notes->where('classroom_student_id', '<>', $record->policy)->whereIn('course_id', $teaching['courses']->pluck('id'))->groupBy('classroom_student_id');
                            $array_notes = [];
                            $ut_average = 0;
                            //dd(array_unique($policies->toArray()));
                            foreach(array_unique($policies->toArray()) as $value) {
                                $notes = $seq->notes->where('classroom_student_id', $value)->whereIn('course_id', $teaching['courses']->pluck('id'));
                                $ut_average = 0;
                                foreach($notes as $note) {
                                    // save unit teaching note to array
                                    $ut_average += $note['value'] * $note->course->coefficient;
                                }
                                array_push($array_notes, $ut_average / $teaching->coefficient);
                            }
                            
                        @endphp
                        @foreach($teaching['courses'] as $course)
                            <tr>
                                <td style="border: 1px solid black; text-align: left; text-transform: capitalize">{{ $course['name'] }}</td>
                                <td style="border: 1px solid black; text-align: right">{{ $policy->notes()->where('sequence_id', $seq->id)->where('course_id', $course['id'])->first() ? $policy->notes()->where('sequence_id', $seq->id)->where('course_id', $course['id'])->first()->value : 0 }}</td>
                                <td style="border: 1px solid black; text-align: right">{{ $course['coefficient'] }}</td>
                                <td style="border: 1px solid black; text-align: right">{{ $policy->notes()->where('sequence_id', $seq->id)->where('course_id', $course['id'])->first() ? $policy->notes()->where('sequence_id', $seq->id)->where('course_id', $course['id'])->first()->value * $course['coefficient']  : 0 }}</td>
                                <td style="border: 1px solid black; text-align: right">
                                    @php
                                        $position = 1;
                                        $val = $policy->notes()->where('sequence_id', $seq->id)->where('course_id', $course['id'])->first() ? $policy->notes()->where('sequence_id', $seq->id)->where('course_id', $course['id'])->first()->value : 0;
                                        $course_notes = $seq->notes()->where('course_id', $course['id'])->get();
                                        foreach($course_notes as $item) {
                                            if($item->value > $val) {
                                                $position++;
                                            }
                                        }
                                        echo $position;
                                        $total_teaching += $val * $course['coefficient'];
                                        $total_coefficient += $course['coefficient']; 
                                    @endphp
                                </td>
                                <td style="border: 1px solid black; text-align: left">
                                    @php
                                        $value = $policy->notes()->where('sequence_id', $seq->id)->where('course_id', $course['id'])->first() ? $policy->notes()->where('sequence_id', $seq->id)->where('course_id', $course['id'])->first()->value : 0;
                                        if($value >= 18 && $value <= 20) {
                                            echo 'Excellent';
                                        } else if($value >= 15 && $value < 18) {
                                            echo 'Trés bien';
                                        } else if($value >= 14 && $value < 15) {
                                            echo 'Bien';
                                        } else if($value >= 12 && $value < 14) {
                                            echo 'Assez bien';
                                        } else if($value >= 10 && $value < 12) {
                                            echo 'Passable'; 
                                        } else {
                                            echo 'Insuffisant';
                                        }     
                                    @endphp
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="6" style="background-color: rgb(163 163 163); border: 1px solid black; padding-top: 10px; padding-bottom: 10px">
                                <div style="display: flex; justify-content: space-between">
                                    <span style="text-transform: uppercase">{{ $teaching['name'] }}</span>
                                    <span>Coefficient: {{ $total_coefficient }}</span>
                                    <span>Total: {{ $total_teaching }}</span>
                                    <span>Moyenne: {{ round($total_teaching / $total_coefficient, 2) }}</span>
                                    <span>
                                        @php
                                            // Teaching Unit student range
                                            $ut_range = 1;
                                            foreach($array_notes as $value) {
                                                if(($total_teaching / $total_coefficient) > $value) {
                                                    $ut_range++;
                                                }
                                            }
                                        @endphp
                                        Rang: {{ $ut_range }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @php
                            $semester_total_coefficient += $total_coefficient;
                            $semester_total_pound += $total_teaching;
                            $semester_range += $ut_range ;
                        @endphp
                    @endif
                @endforeach
    
            </tbody>

        </table>
        <div style="margin-top: 1px; display: flex; justify-content: space-between; background-color: rgb(163 163 163); border: 1px solid black; padding-top: 10px; padding-bottom: 10px">
            <span style="text-transform: uppercase">resultats sequentiels</span>
            <span>Coefficient: {{ $semester_total_coefficient }}</span>
            <span>Total: {{ $semester_total_pound }}</span>
            <span>Moyenne: {{ round($semester_total_pound / $semester_total_coefficient, 2) }}</span>
            <span>Rang: {{ $range }}</span>
        </div>
    </div>

    <div style="display: flex; column-gap: 10px; margin-top: 120px">
        <div style="width: 50%">
            <table style="border-collapse: collapse;border: 1px solid black; width: 100%">
                <tbody>
                    <tr>
                        <td style="background-color: rgb(163 163 163); text-align: center" colspan="8">
                            Décisions du conseil de classe
                        </td>
                    </tr>
                    <tr style="background-color: rgb(163 163 163)">
                        <td style="text-align: center; border: 1px solid black" colspan="3">DISCIPLINE</td>
                        <td style="text-align: center; border: 1px solid black" colspan="4">TRAVAIL</td>
                    </tr>
                    <tr>
                        <td rowspan="2" style="border: 1px solid black">Absences</td>
                        <td style="border: 1px solid black; font-weight: bold">J</td>
                        <td style="border: 1px solid black; text-align: center; font-weight: bold">{{ count($policy->absences()->where('status', true)->get()) }}</td>
                        <td style="border: 1px solid black; text-align: center; font-weight: bold">TB</td>
                        <td style="border: 1px solid black; text-align: center; font-weight: bold">B</td>
                        <td style="border: 1px solid black; text-align: center; font-weight: bold">AB</td>
                        <td style="border: 1px solid black; text-align: center; font-weight: bold">PASS</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; font-weight: bold">NJ</td>
                        <td style="border: 1px solid black; text-align: center; font-weight: bold">{{ count($policy->absences()->where('status', false)->get()) }}</td>
                        <td style="border: 1px solid black; text-align: center; font-weight: bold">MED</td>
                        <td style="border: 1px solid black; text-align: center; font-weight: bold">INS</td>
                        <td style="border: 1px solid black; text-align: center; font-weight: bold">FAIB</td>
                        <td style="border: 1px solid black; text-align: center; font-weight: bold">NUL</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black" colspan="2">Avertissement</td>
                        <td style="border: 1px solid black"></td>
                        <td style="border: 1px solid black;" rowspan="3" colspan="4">
                            <div style="display: flex: flex-direction: column; width: 100%; height: 100%; line-height: 0.1">
                                <p style="text-align: end; font-size: 15px;  margin-right: 10px">Un effort s'impose en</p>
                                <p style="border-bottom: 1px solid black; margin-left: 10px; margin-right: 10px"></p>
                                <p style="border-bottom: 1px solid black; margin-left: 10px; margin-right: 10px"></p>
                                <p style="border-bottom: 1px solid black; margin-left: 10px; margin-right: 10px"></p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black" colspan="2">Blâmes de conduite</td>
                        <td style="border: 1px solid black"></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black" colspan="2">Exclusions (en jours)</td>
                        <td style="border: 1px solid black"></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; text-align: center; background-color: rgb(163 163 163)" colspan="3">Visa des parents ou tuteurs</td>
                        <td style="border: 1px solid black; text-align: center; background-color: rgb(163 163 163)" colspan="4">Observation et visa du professeur titulaire</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; height: 70px" colspan="3"></td>
                        <td style="border: 1px solid black; height: 70px" colspan="4"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="width: 50%">
            <div style="display: flex; background-color: rgb(163 163 163); border: 1px solid black">
                <div style="display: flex; flex-direction: column; width: 60%; border-right: 1px solid black; font-size: 14px; row-gap: 5px">
                    <span>Elève/Classe</span>
                    <div style="display: flex; justify-content: space-around">
                        <span>Rang</span>
                        <span>{{ $range }}</span>
                        <span>Max</span>
                        <span>{{ round($statistics->max('average'), 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-around">
                        <span>M</span>
                        <span>{{ round($semester_total_pound / $semester_total_coefficient, 2) }}</span>
                        <span>Min</span>
                        <span>{{ round($statistics->min('average'), 2) }}</span>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; width: 40%">
                    <span style="font-size: 15px">MENTION</span>
                    <div style="display: flex; justify-content: center; text-transform: uppercase">Félicitation</div>
                    <div style="border-top: 1px solid black; width: 100%"></div>
                </div>
            </div>
            <div style="border: 1px solid black; margin-top: 57px">
                <div style="display: flex; justify-content: center; background-color: rgb(163 163 163);">Visa du principal</div>
                <div style="border-top: 1px solid black; height: 100px"></div>
            </div>
        </div>
    </div>

</div>