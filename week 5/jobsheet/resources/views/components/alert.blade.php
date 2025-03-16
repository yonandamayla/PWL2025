<div class="alert alert-danger">
    {!! $slot !!}
</div>
@extends('layouts.app')
{{-- kode --}}
@component("alert")
    <b>Tulisan ini akan mengisi variable $slot</b>
@endcomponent