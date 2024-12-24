{{--
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
--}}

@extends('layouts.dashboard')


@section('submenu')
<div class="links h-100">
	<ul class="nav h-100">
		<li class="nav-item">
			<a href="{{route('home')}}" role="button" class="d-none d-md-flex nav-link text-white">
				<i class="fa fa-home d-block text-center" style="font-size: 1.25rem"></i>
				<small>Dashboard</small>
			</a>
		</li>
		<li class="nav-item">
			<a href="{{route('categories.index')}}" class="nav-link text-white">
				<i class="fa fa-ban d-block text-center" style="font-size: 1.25rem"></i>
				<small>Cancel</small>
			</a>
		</li>
	</ul>
</div>
@endsection

@section('content')
<section class="container bg-white h-100 shadow">
	<div class="row justify-content-md-center h-100">
		<div class="col-12 px-md-4">
			<form action="{{route('categories.store')}}" method="post" class="main-form position-relative h-100" x-watch="isFormValid()">
				<h5>Harap isi form dibawah ini.</h5>
				<div class="form-group row mb-3 mx-0">
					@csrf
					<label class="col-lg-4 ps-0 col-form-label" for="name">Nama <small class="text-danger">*</small></label>
					<input type="text" name="name" id="name" class="form-control col-lg" required="required" x-model="required.name">
					@error('name')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>

				<div class="w-100 clearfix py-2"></div>
				<div class="row position-absolute w-100 mx-0 absolute" style="bottom: 0;">
					<div class="col-md-6 offset-md-6 px-0">
						<button type="submit" class="btn btn-primary d-flex w-100 justify-content-center align-items-center" :disabled="!isFormValid()">
							<i class="fa fa-save me-1"></i>Save
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
@endsection
@section('js')
<script>
	document.addEventListener('alpine:init', () => {
		console.log("APLINE INIT SUCCESS")
		Alpine.data('form', () => ({
			option: {},
			required: {
				name: '',
			},
			valid: false,
			init() {
			},
			isFormValid(){
				for(let key in this.required){
					if(this.required[key] == '' && key!='phone_number'){
						this.valid = false;
						break;
					}else if(key=='phone_number' && this.option.has_phone_number && this.required[key] == ''){
						this.valid = false;
						break;
					}else{
						this.valid = true;
					}
				}
				// console.log('formData:',this.required,'Valid:',this.valid)
				return this.valid;
			},
		}));
		let masterContent = document.querySelector('.master-content')
		masterContent.setAttribute('x-data', 'form')
	})
</script>
@endsection