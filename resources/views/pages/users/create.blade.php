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
			<a href="{{route('users.index')}}" class="nav-link text-white">
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
			<form action="{{route('users.store')}}" method="post" class="main-form position-relative h-100" x-watch="isFormValid()">
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
				<div class="form-group row mb-3 mx-0">
					<label class="col-lg-4 ps-0 col-form-label" for="email">Email <small class="text-danger">*</small></label>
					<input type="email" name="email" id="email" class="form-control col-lg" required="required" x-model="required.email">
					@error('email')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>
				<div class="form-group row mb-3 mx-0">
					<label class="col-lg-4 ps-0 col-form-label" for="phone_number">No. Telepon <small class="text-danger">*</small></label>
					<div class="col-lg px-0">
						<div class="input-group">
							<span class="input-group-text">+62</span>
							<input type="text" name="phone_number" id="phone_number" class="form-control col-lg" required="required" x-model="required.phone_number" :disabled="!option.has_phone_number">
						</div>
						<div class="float-end">
							<label for="has_phone_number">
								<input type="checkbox" id="has_phone_number" class="form-check-input" x-model="option.has_phone_number"> Has Phone Number
							</label>
						</div>
					</div>
					@error('phone_number')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>
				<div class="form-group row mb-3 mx-0">
					<label class="col-lg-4 ps-0 col-form-label" for="password">Password <small class="text-danger">*</small></label>
					<input type="password" name="password" id="password" class="form-control col-lg" required="required" x-model="required.password">
					@error('password')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>
				<div class="form-group row mb-3 mx-0">
					<label class="col-lg-4 ps-0 col-form-label" for="re_password">Re-Password <small class="text-danger">*</small></label>
					<input type="password" name="re_password" id="re_password" class="form-control col-lg" required="required" x-model="required.re_password">
					@error('re_password')
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
			option: {
				has_phone_number: true
			},
			required: {
				name: '',
				email: '',
				phone_number: '',
				password: '',
				re_password: ''
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