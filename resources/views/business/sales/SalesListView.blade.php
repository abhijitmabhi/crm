@extends('layouts.BaseView')

@section('header')
    <h4 class="navbar-title">Vertragsabschlüsse</h4>
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <b-card header="Vertragsabschlüsse" header-class="simple-card-header" header-tag="h4">
                    <div>
                        <h4 class="text-primary">
                            <i class="fas fa-file-signature"></i> Neue Vertragsabschlüsse
                        </h4>
                        <div class="row">
                            <div class="col text-bold text-lg">Kunde</div>
                            <div class="col text-bold text-lg">SAM</div>
                            <div class="col text-bold text-lg">Produkt & Preis</div>
                            <div class="col text-bold text-lg">Finanzierung</div>
                            <div class="col text-bold text-lg">Aktion</div>
                        </div>
                        @foreach($openSales as $sale)
                            <sales-row
                                    :customer="{{json_encode($sale->customer->company)}}"
                                    :product="{{json_encode($sale->product)}}"
                                    :expert="{{json_encode($sale->expert)}}"
                                    :payment-option="{{json_encode($sale->paymentOption ?? 'unset')}}"
                                    :sold-at="{{$sale->price}}"
                                    :id="{{$sale->id}}"
                            ></sales-row>
                        @endforeach
                    </div>
                    <div class="pt-4 pb-4">
                        <div class="row">
                            <div class="flex-grow-1 col">
                                <h4 class="text-primary">
                                    <i class="fas fa-file-contract"></i> Bestätigte Verträge ({{number_format($salesTotal, 2, ',', '.')}} €)
                                </h4>
                            </div>
                            <div>
                                <sales-control :experts="{{$experts}}"></sales-control>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-bold text-lg">Kunde</div>
                            <div class="col text-bold text-lg">SAM</div>
                            <div class="col text-bold text-lg">Produkt & Preis</div>
                            <div class="col text-bold text-lg">Finanzierung</div>
                        </div>
                        @foreach($oldSales as $sale)
                            <sales-row
                                    :customer="{{json_encode($sale->customer->company)}}"
                                    :product="{{json_encode($sale->product)}}"
                                    :expert="{{json_encode($sale->expert)}}"
                                    :sold-at="{{$sale->price}}"
                                    :id="{{$sale->id}}"
                                    :payment-option="{{json_encode($sale->paymentOption)}}"
                                    :accepted="true"
                            ></sales-row>
                        @endforeach
                    </div>
                </b-card>
            </div>
        </div>
    </div>
@endsection