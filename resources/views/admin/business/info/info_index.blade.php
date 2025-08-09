<div class="bg-blue">
    @extends('admin.admin_main')
    
    @section('title', 'Admin: Categories')
    
    @section('css')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/review.css') }}"> --}}
    @endsection
    
    @section('sub_content')
    
    
            <form action="{{route('admin.details.store')}}" method="post" class="row gx-2 mb-4">
            @csrf       
    
                        <div class="col-4">
                            {{-- <label for="name" ></label> --}}
                            <input type="text" name="name" id="name" class="form-control " placeholder="Add an Item..." value="{{old('name')}}">
                            @error('name')
                                <p class="mb-0 text-danger small">{{$message}}</p>
                            @enderror
                        </div>     
                        {{-- category 選択 --}}
                        <div class="col-4">
                            <select name="business_info_category_id" class="form-select">                             
                                <option value="" disabled selected hidden class="">Select a category...</option>    
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('business_info_category_id') == $category->id ? 'selected' : '' }} >
                                        {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('business_info_category_id')
                                <p class="mb-0 text-danger small">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn  btn-primary "><i class="fa-solid fa-plus"></i> Add</button>
                        </div> 
    
       
            </form>
    
    
    
        <table class="table border bg-white table-hover align-middle text-secondary">
            <thead class="table-warning text-secondary text-uppercase small">
                <tr>
                    <th class="align-middle">id</th>
                    <th class="align-middle">Name</th>
                    <th class="align-middle">Category</th>
                    <th class="align-middle">Last Updated</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($all_info as $detail)
                    <tr >
                        <td >
                            {{$detail->id}}
                        </td>
                        <td class="text-dark">
                            {{$detail->name}}
                        </td>
                        <td class="text-dark">
                            {{$detail->category->name}}
                        </td>
                        <td>
                            @if($detail->updated_at > '2000-01-01 00:00:00')
                                {{date('M d, Y H:m:s', strtotime($detail->updated_at))}}
                            @endif
                        </td>
                        <td>
                            <div class="m-auto">
                                {{-- edit --}}
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit-detail{{$detail->id}}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                &nbsp;
                                {{-- delete --}}
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-detail{{$detail->id}}">
                                    <i class="fa-solid fa-trash "></i>
                                </button>
    
    
    
                            </div>
    
                            @include('admin.businesses.info.actions')
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end mb-5">
            {{ $all_info->links() }}
        </div>
    
    
    @endsection