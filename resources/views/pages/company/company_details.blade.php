@extends('layouts.app')

@section('head-needMapScript', 'ON')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m4 l3 mt-2 center-align">
                @if (isset($company->link))
                    <img class="responsive-img" src="{{ asset($company->link) }}" alt="{{$company->name}}">
                @else
                    <img class="responsive-img" src="https://via.placeholder.com/300" alt="{{$company->name}}">
                @endif
            </div>
            <div class="col s12 m8 l9">
                <h1>{{$company->name}}</h1>
                <p>
                    <span class="title-category">{{$company->category->name}}</span><br>
                    {{$company->description}}
                </p>
                <p>
                    <strong>Téphone :</strong> {{$company->phone}}<br>
                    <strong>Adresse :</strong> {{$company->adress1}} {{$company->adress2 || ''}}, {{$company->city->code_postal}} {{$company->city->name}}<br>
                    <strong>Siret   :</strong> {{$company->siret}}
                    <strong>Créé le :</strong> {{$company->created_at}}
                </p>
            </div>
        </div>

        @isMyCompany($company->user_id)
            <a href="{{route('company_edit',  ['company_id' => $company->id])}}" class="btn">Modifier <i class="fas fa-edit"></i></a>
            <a href="{{route('activity_add_get', ['company_id' => $company->id])}}" class="btn">Ajouter une activité <i class="fas fa-plus-square"></i></a>
        @endisMyCompany
        @isAdmin
        @if($company->state==0)
            <a href="{{ route('confirm_company', ['company_id' => $company->id]) }}" class="btn" title="Validée entreprise"><i class="fas fa-check"></i></a>
            <a href="{{ route('refuse_company', ['company_id' => $company->id]) }}" class="btn"  title="Refusée entreprise"><i class="fas fa-ban"></i></a>
        @else
            <a href="{{ route('refuse_company', ['company_id' => $company->id]) }}" class="btn" title="Supprimer entreprise"><i class="fas fa-ban"></i></a>
        @endif
        @endisAdmin

        <div class="row mt-2">
            @forelse($activities_activer as $activity)
                <div class="col s12 m12 l4">
                        <div class="card">
                        <div class="card-image">
                            @if (isset($activity->link0))
                                <img src="{{asset($activity->link0)}}" alt="{{$activity->name}}">
                            @else
                                <img src="https://via.placeholder.com/300" alt="{{$activity->name}}">
                            @endif

                            <div class="price">
                                <span>
                                    {{$activity->price}} €
                                </span>
                                <span>
                                    <a class="btn" href="{{route('activity_details', ['activity_id' => $activity->id])}}">
                                        <i class="fas fa-cart-arrow-down"></i>
                                    </a>
                                </span>
                            </div>

                        </div>
                        <div class="card-stacked">
                            <div class="card-content">
                                <h5>{{$activity->name}}</h5>
                                <p>
                                    <div class="mb-1">
                                        <span class="categori-show" style="background : {{ \App\SubCategory::find($activity->sub_category_id)->category->hexa }}"></span>
                                        {{ \App\SubCategory::find($activity->sub_category_id)->category->name }}<br>
                                        @if ($activity->note != null)
                                            {{$activity->note}} / 5 @include('components.star', ['note' => $activity->note])
                                        @else
                                            Aucune note
                                        @endif<br>
                                    </div>
                                    {{\Illuminate\Support\Str::limit($activity->description, 150, $end='...') }}
                                </p>
                            </div>
                            <div class="card-action">
                                <a href="{{route('activity_details', ['activity_id' => $activity->id])}}">En voir plus</a>
                                @isMyCompany($company->user_id)
                                    <a class="btn" href="{{route('change_state_activity', ['activity_id' => $activity->id, 'state' => '-1'])}}">Désactiver</a>
                                @endisMyCompany
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <h3>Cette entreprise n'a pas encore de formule</h3>
            @endforelse


            {{ $activities_activer->links('components.pagination') }}
        </div>


        @isMyCompany($company->user_id)
            <h4>Vos activités en cours de validation ou désactivées</h4>
            <div class="row mt-2">
                @forelse($activities_forValideOrNotActi as $activity)
                    <div class="col s12 m12 l4">
                        <div class="card">
                            <div class="card-image">
                                @if (isset($activity->link0))
                                    <img src="{{asset($activity->link0)}}" alt="{{$activity->name}}">
                                @else
                                    <img src="https://via.placeholder.com/300" alt="{{$activity->name}}">
                                @endif

                                <div class="price">
                                    <span>
                                        {{$activity->price}} $
                                    </span>
                                    <span>
                                        <a class="btn" >
                                            <i class="fas fa-cart-arrow-down"></i>
                                        </a>
                                    </span>
                                </div>

                            </div>
                            <div class="card-stacked">
                                <div class="card-content">
                                    <h5>{{$activity->name}}</h5>
                                    <p>
                                        <div class="mb-1">
                                            <span class="categori-show" style="background : {{ \App\SubCategory::find($activity->sub_category_id)->category->hexa }}"></span>
                                            {{ \App\SubCategory::find($activity->sub_category_id)->category->name }}<br>
                                            @if ($activity->note != null)
                                                {{$activity->note}}  @include('components.star', ['note' => $activity->note])
                                            @else
                                                Aucune note
                                            @endif<br>
                                        </div>
                                        {{\Illuminate\Support\Str::limit($activity->description, 150, $end='...') }}
                                    </p>
                                </div>
                                <div class="card-action">
                                @if ($activity->state == -1)
                                    <a href="{{route('change_state_activity', ['activity_id' => $activity->id, 'state' => '1'])}}">Activer</a>
                                @else
                                    Entreprise en cours de validation <i class="fas fa-hourglass-half"></i>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <h5>Cette entreprise n'a pas d'activité en cours de validation ou désactivée</h5>
                @endforelse
            </div>
        @endisMyCompany
    </div>
@endsection
