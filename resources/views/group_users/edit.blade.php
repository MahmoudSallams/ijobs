@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Group User
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($groupUser, ['route' => ['groupUsers.update', $groupUser->id], 'method' => 'patch']) !!}

                        @include('group_users.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection