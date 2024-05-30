@extends('layouts.app')
@section('content')
        <table class="table-auto">
            <thead>
                <tr>
                    <th>Raison</th>
                    <th>Montant</th>
                    <th>Date</th>
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
