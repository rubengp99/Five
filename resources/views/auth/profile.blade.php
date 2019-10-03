@extends('layouts.app')

@section('content')
    <div class="container" style="transform: translateY(15%);">
        <div class="row">
            @if ($message = Session::get('success'))

                <div class="alert alert-success alert-block">

                    <button type="button" class="close" data-dismiss="alert">×</button>

                    <strong>{{ $message }}</strong>

                </div>

            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="row justify-content-center">

            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-body justify-content-center" style="padding-bottom: 40px;">
                        <div id="avatar" class="profile-header-img" style="background:url(/storage/{{ Auth::user()->image }})">
                            <!-- 
                            <div class="rank-label-container" >
                                <br>
                                <span style="font-size:15px;" class="label label-default rank-label">{{Auth::user()->name}}</span>
                            </div>
                            badge -->
                        </div>
                        <br>
                        <form action="/profile" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="box">
                                <input type="file" name="avatar" id="avatarFile" class="inputfile inputfile-2">
                                <label for="avatarFile"><i class="fa fa-paperclip text-white"></i><span class="text-white">Update your avatar...</span></label>
                            </div>
                            <small style="padding: 0px 50px 0px 50px;display: block;margin-top:10px;" class="form-text text-white">Please upload a valid image file. Size of image should not be more than 2MB.</small>
                            <br>
                            <div class="form-group">
                                <label for="new_name" class="col-md-4 control-label text-white">Name</label>
                                <div class="col-md-6" style="margin-left:5px">
                                    <input id="new_name" type="text" class="form-control text-black" name="new_name" value="{{Auth::user()->name}}" required>
                                </div>
                            </div>
                            <div class="form-group" style="transform: translateY(10px);">
                                <label for="new_email" class="col-md-4 control-label text-white">Change Email</label>
                                <div class="col-md-6" style="margin-left:5px">
                                <input id="new_email" type="email" class="form-control text-black" name="new_email" value="{{Auth::user()->email}}" required>
                                </div>
                            </div>
                            <div class="form-group" style="transform: translateY(20px);">
                                <label for="new_password" class="col-md-4 control-label text-white">Change Password</label>
                                <div class="col-md-6" style="margin-left:5px">
                                    <input id="new_password" type="password" class="form-control text-black" name="new_password">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-12" >
                                    <button type="submit" class="btn btn-primary" style="transform: translateY(30px);">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection