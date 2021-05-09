@extends('layouts.BaseView')

@section('header')
   <h4>Benachrichtungen</h4>
@endsection

@section('nav-links')
    @breadcrumbs([
        'crumbs' => [
            [ 'link' => '/notifiactions', 'name' => 'Benachrichtungen']
        ]
    ])
    @endbreadcrumbs
@endsection

@section('main-content')
<div>
    <b-card no-body>
        <b-tabs card content-class="mt-3">
            <b-tab title="Gelesen" active>
                <table class="table-striped table table-sm table-responsive-sm">  
                    <tbody>
                        @foreach ($readNotifications as $notification)
                            <tr>
                            <td><i class="fas {{$notification->icon}} text-primary"></i></td>
                                <td>
                                    {{$notification->created_at->diffForHumans()}}
                                    <a href="{{$notification->link}}">
                                        {{$notification->data['message']}}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </b-tab>
            <b-tab title="Ungelesen" >      
                <table class="table-striped table table-sm table-responsive-sm">  
                    <tbody>
                        @foreach ($unreadNotifications as $notification)
                            <tr>
                            <td><i class="fas {{$notification->icon}} text-primary"></i></td>
                                <td>
                                    {{$notification->created_at->diffForHumans()}}
                                    <a href="{{$notification->link}}">
                                        {{$notification->data['message']}}
                                    </a>
                                </td>
                                {{-- <td><button class="btn btn-white">Lesen</button></td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </b-tab>
        </b-tabs>
    </b-card>
</div>
@endsection