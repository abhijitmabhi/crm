@extends('layouts.BaseView')

@section('header')
<h4 class="navbar-title">SAM Pipeline</h4>
@endsection

@section('main-content')
<div class="card">
    <div class="card-header simple-card-header d-flex align-items-center">
        <h3 class="card-title">
            <i class="fas fa-map-marker-alt"></i> Pipeline {{$expert->name}}
        </h3>
    </div>

    <div class="card-body">
{{--    TODO: move to different location    --}}
        <expert-call-center-agent-assignment
                route-submit="{{ route('experts.assign') }}"
                route-success="{{ route('admin.experts.index') }}"
                :expert-id="{{ $expert->id }}"
                :all-agents="{{ $allAgents }}"
                :expert-agents="{{ $expertAgents }}">
            {{ csrf_field() }}
        </expert-call-center-agent-assignment>

        <expert-lead-category-select
                label-text="Branchen zuweisen"
                route-submit="{{ route('expert.category.prioritize') }}"
                route-success="{{ route('admin.experts.index') }}"
                :expert-id="{{ $expert->id }}"
                :all-categories="{{ $categories }}"
                :expert-categories="{{ $expertCategories }}"
                :category-stats="{{ $categoryStats }}">
            {{ csrf_field() }}
        </expert-lead-category-select>

        <expert-lead-category-select
                label-text="Branchen ausschließen"
                route-submit="{{ route('expert.category.exclude') }}"
                route-success="{{ route('admin.experts.index') }}"
                :expert-id="{{ $expert->id }}"
                :all-categories="{{ $categories }}"
                :expert-categories="{{ $excludedCategories }}"
                :category-stats="{{ $categoryStats }}">
            {{ csrf_field() }}
        </expert-lead-category-select>
    </div>
</div>
@endsection