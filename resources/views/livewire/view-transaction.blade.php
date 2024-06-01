@extends('layouts.app')
@section('css')
    <style>
        #table {     
            border: 1px solid #eee;
            border-collapse: collapse;
        }
        #table td {     
            border-bottom: 1px solid #ddd;
            text-align: center;
            height: 50px;
        }
        .header {     
            border-bottom: 1px solid #ddd;
            height: 50px;
        }

    </style>
@endsection
@section('content')
    <div>
        <h1>Liste des Versements de  {{ $name }} </h1>
    </div>
    <table id="table">
        <thead>
            <tr class="bg-gray-50">
                <th class="header">Raison</th>
                <th class="header">Montant</th>
                <th class="header">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
                <tr>
                    <td>{{ $trx->name }}</td>
                    <td>{{ $trx->amount }} FCFA</td>
                    <td>{{ $trx->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
