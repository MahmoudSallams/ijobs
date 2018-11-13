@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            User Company
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($userCompany, ['route' => ['userCompanies.update', $userCompany->id], 'method' => 'patch']) !!}

                        @include('user_companies.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection