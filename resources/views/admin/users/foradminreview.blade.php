<div class="bg-blue">
@extends('admin.admin_main')

@section('title', 'For Review')

@section('sub_content')
<link rel="stylesheet" href="{{asset('css/style.css')}}"  /> 

  
    
<!-- Header image -->
    {{-- <div class="row">
        <div class="pt-2 px-0">
            @if($user->header)
                <img src="{{$user->header}}" class="header-image"  alt="">
            @else
                <i class="fa-solid fa-image text-secondary icon-xl d-block text-center"></i>
            @endif
        </div>
    </div> --}}
    
    <div class="row justify-content-center pt-2">
        <div class="col-10">  
            <div class="row mb-3">
                <h3>Applied User Information</h3>
            </div>
        </div>
    </div>
    
    <div class="pb-5 row justify-content-center pt-2">
        <div class="col-10">  
            <div class="row mb-3">
                <label for="name" class="form-label">Header photo</label>
                <div class="pt-2 px-0">
                    @if($user->header)
                        <img src="{{$user->header}}" class="header-image"  alt="">
                    @else
                        <i class="fa-solid fa-image text-secondary icon-xl d-block text-center"></i>
                    @endif
                </div>
            </div>
            <div class="row mb-3">                
                <!-- Avatar image -->
                <div class="col-auto profile-image">
                    <label for="name" class="form-label">Avatar image</label>
                    @if($user->avatar)
                        <img src="{{$user->avatar}}" alt="" class="rounded-circle avatar-xl d-block mx-auto">
                    @else
                        <i class="fa-solid fa-circle-user text-secondary profile-xl d-block text-center"></i>
                    @endif
                </div>
            </div>
            {{-- User information --}}
            <div class="row mb-3">
                <div class="col">
                    <label for="name" class="form-label">Business user name<span class="color-red">*</span></label>
                    <div class="h4">{{$user->name}}</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <label for="email" class="form-label">E-mail address<span class="color-red">*</span></label>
                    <div class="h4">{{$user->email}}</div>
                </div>
                <div class="col-6">
                    <label for="website_url" class="form-label">Website URL</label>
                    @if($user->website_url)
                        <div class="h4">{{$user->website_url}}</div>
                    @else
                        <div>---</div>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <label for="zip" class="form-label">ZIP code<span class="color-red">*</span></label>
                    @if($user->website_url)
                        <div class="h4">{{$user->zip}}</div>
                    @else
                        <div>---</div>
                    @endif
                </div>
                <div class="col-6">
                    <label for="phonenumber" class="form-label">Phone number<span class="color-red">*</span></label>
                    @if($user->phonenumber)
                        <div class="h4">{{$user->phonenumber}}</div>
                    @else
                        <div>---</div>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="address" class="form-label">Address<span class="color-red">*</span></label>
                    @if($user->address)
                        <div class="h4">{{$user->address}}</div>
                    @else
                        <div>---</div>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="introduction" class="form-label">Introduction<span class="color-red">*</span></label>
                    @if($user->introduction)
                        <div class="h4">{{$user->introduction}}</div>
                    @else
                        <div>---</div>
                    @endif
                </div>    
            </div>
    
            <!-- SNS -->
            <div class="row mb-5">
                <div class="col">
                    <div class="row mb-2">
                        <div class="col">
                            <label for="sns" class="form-label">Social media</label>
                        </div>
                    </div>
                </div> 
                <div class="row mb-2">
                    <div class="col-6">   
                        <div class="row">
                            <div class="col-2">
                                <label for="instagram" class="form-label"><i class="fa-brands fa-instagram text-dark icon-md pe-2"></i></label>
                            </div>
                            <div class="col">
                                @if($user->instagram)
                                    <div class="h4">{{$user->instagram}}</div>
                                @else
                                    <div>---</div>
                                @endif
                            </div>
                        </div>
                    </div>  
                    <div class="col-6">
                        <div class="row">
                            <div class="col-2">
                                <label for="facebook" class="form-label"><i class="fa-brands fa-facebook text-dark icon-md pe-2"></i></label>
                            </div>
                            <div class="col">
                                @if($user->facebook)
                                    <div class="h4">{{$user->facebook}}</div>
                                @else
                                    <div>---</div>
                                @endif
                            </div>
                        </div>    
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">   
                        <div class="row">
                            <div class="col-2">
                                <label for="x" class="form-label"><i class="fa-brands fa-x-twitter text-dark icon-md pe-2"></i></label>
                            </div>
                            <div class="col">
                                @if($user->x)
                                    <div class="h4">{{$user->x}}</div>
                                @else
                                    <div>---</div>
                                @endif
                            </div>
                        </div>
                    </div>  
                    <div class="col-6">
                        <div class="row">
                            <div class="col-2">
                                <label for="tiktok" class="form-label"><i class="fa-brands fa-tiktok text-dark icon-md pe-2"></i></label>
                            </div>
                            <div class="col">
                                @if($user->tiktok)
                                    <div class="h4">{{$user->tiktok}}</div>
                                @else
                                    <div>---</div>
                                @endif
                            </div>
                        </div>    
                    </div>
                </div>
            </div>

            <div class="row mb-5 justify-content-center">
                @if($user->official_certification == 2)
                    <div class="col-4">
                        <form method="POST" action="{{ route('admin.users.certify', $user->id) }}">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="btn btn-sm btn-green w-100">Approve</button>
                        </form>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-4">
                        <form method="POST" action="{{ route('admin.users.certify', $user->id) }}">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="btn btn-sm btn-red w-100">Reject</button>
                        </form>
                    </div>
                @elseif($user->official_certification == 3)
                    <div class="col-4">
                        <button class="btn btn-sm btn-outline-green w-100 mb-3">Approved</button>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-4">
                        <form method="POST" action="{{ route('admin.users.certify', $user->id) }}">
                            @csrf
                            <input type="hidden" name="action" value="revoke">
                            <button type="submit" class="btn btn-sm btn-navy w-100 ">Revoke</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>

@endsection



