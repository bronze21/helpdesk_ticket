{{--
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
--}}

@extends('layouts.dashboard')


@section('submenu')
<div class="links h-100">
	<ul class="nav h-100">
		<li class="nav-item">
			<a href="{{route('home')}}" role="button" class="d-none d-md-flex nav-link text-dark">
				<i class="fa fa-home d-block text-center" style="font-size: 1.25rem"></i>
				<small>Dashboard</small>
			</a>
		</li>
		<li class="nav-item">
			<a href="{{route('users.index')}}" class="nav-link text-dark">
				<i class="fa fa-ban d-block text-center" style="font-size: 1.25rem"></i>
				<small>Cancel</small>
			</a>
		</li>
	</ul>
</div>
@endsection

@section('content')
<section class="container-fluid">
	<div class="row mx-0">
		<div class="col-12 card mx-0 px-0">
			<form action="{{route('users.update',$data->id)}}" method="post" class="main-form position-relative h-100 card-body" x-watch="isFormValid('required')">
				<h4 class="card-title">Profile Data</h4>
				<div class="form-group row mb-3 mx-0">
					@csrf
					@method('PUT')
					<label class="col-lg-4 ps-0 col-form-label" for="name">Nama <small class="text-danger">*</small></label>
					<input type="text" name="name" id="name" class="form-control col-lg" required="required" x-model="required.name" value="{{$data?->name ?? ''}}">
					@error('name')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>
				<div class="form-group row mb-3 mx-0">
					<label class="col-lg-4 ps-0 col-form-label" for="email">Email <small class="text-danger">*</small></label>
					<input type="email" name="email" id="email" class="form-control col-lg" required="required" x-model="required.email" value="{{$data?->email ?? ''}}">
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
							<input type="text" name="phone_number" id="phone_number" class="form-control col-lg" :required="option.has_phone_number" x-model="required.phone_number" :disabled="!option.has_phone_number" value="{{ ltrim($data?->phone_number, '62') }}" @@input="phoneNumber('required.phone_number',16)">
						</div>
						<div class="float-end">
							<label for="has_phone_number">
								<input type="checkbox" id="has_phone_number" class="form-check-input" x-model="option.has_phone_number" @checked(!empty($data->phone_number))> Has Phone Number
							</label>
						</div>
					</div>
					@error('phone_number')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>
				<div class="w-100 clearfix py-2"></div>
				<div class="row w-100 mx-0" style="bottom: 0;gap: 1rem;">
					<div class="col-lg col-12 px-0">
						<button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#mdl-change_password" x-on:click="option.change_password = !option.change_password"><i class="fa fa-key me-1"></i>Change Password</button>
					</div>
					<div class="col-lg col-12 px-0">
						<button type="submit" class="btn btn-primary d-flex w-100 justify-content-center align-items-center" :disabled="!isFormValid('required')">
							<i class="fa fa-save me-1"></i>Save
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
@endsection
@section('modal')
<div class="modal fade" id="mdl-change_password" x-watch="option.change_password">
	<form class="modal-dialog modal-dialog-centered" role="document" action="{{route('users.update_password',$data->id)}}" method="post" x-watch="isFormValid('change_password')">
		@csrf
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Change Password</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="form-group row mb-3 mx-0">
					<label class="col-lg-4 ps-0 col-form-label" for="old_password">Old Password <small class="text-danger">*</small></label>
					<input type="password" name="old_password" id="old_password" class="form-control col-lg" :required="option.change_password"  value="{{old('old_password')}}" x-model="change_password.old_password" :disabled="!option.change_password">
					@error('old_password')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>
				<div class="form-group row mb-3 mx-0">
					<label class="col-lg-4 ps-0 col-form-label" for="password">Password <small class="text-danger">*</small></label>
					<input type="password" name="password" id="password" class="form-control col-lg" :required="option.change_password"  value="{{old('password')}}" x-model="change_password.password" :disabled="!option.change_password">
					@error('password')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>
				<div class="form-group row mx-0">
					<label class="col-lg-4 ps-0 col-form-label" for="re_password">Re-Password <small class="text-danger">*</small></label>
					<input type="password" name="re_password" id="re_password" class="form-control col-lg" :required="option.change_password"  value="{{old('re_password')}}" x-model="change_password.re_password" :disabled="!option.change_password">
					@error('re_password')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary" :disabled="!isFormValid('change_password')"><i class="fa fa-save me-1"></i><span>Save</span></button>
			</div>
		</div>
	</form>
</div>
@endsection
@section('js')
<script>
	document.addEventListener('alpine:init', () => {
		console.log("APLINE INIT SUCCESS")
		Alpine.data('edit_user', () => ({
			option: {
				has_phone_number: true,
				change_password: false
			},
			required: {
				name: '',
				email: '',
				phone_number: '',
			},
			change_password:{
				old_password: '',
				password: '',
				re_password: ''
			},
			valid: {
				required: false,
				change_password: false
			},
			init() {
				this.required = {
					name: document.getElementById('name').value,
					email: document.getElementById('email').value,
					phone_number: document.getElementById('phone_number').value
				}
				this.change_password = {
					old_password: document.getElementById('old_password').value,
					password: document.getElementById('password').value,
					re_password: document.getElementById('re_password').value,
				}
				this.option.has_phone_number = document.getElementById('has_phone_number').checked
				let MDL_changePassword = document.querySelector('#mdl-change_password')
				if(MDL_changePassword){
					MDL_changePassword.addEventListener('shown.bs.modal', () => {
						this.option.change_password = true;
					})
					MDL_changePassword.addEventListener('hide.bs.modal', () => {
						this.option.change_password = false;
					})
				}
			},
			numericOnly(value, maxChar=12){
				let [key,param]	= value.split('.')
				if(param){
					let xValue = this[key][param].replace(/[^0-9,.]/g, '');
					if(xValue.length>maxChar){
						xValue = xValue.slice(0,maxChar);
					}
					this[key][param] = xValue;
				}else{
					let xValue = this[key].replace(/[^0-9,.]/g, '');
					if(xValue.length>maxChar){
						xValue = xValue.slice(0,maxChar);
					}
					this[key] = xValue;
				}
			},
			phoneNumber(value, maxChar=12){
				let [key,param] = value.split('.')
				if(param){
					let xValue = this[key][param].replace(/[^0-9]/g, '');
					if(xValue.length>maxChar){
						xValue = xValue.slice(0,maxChar);
					}
					this[key][param] = xValue;
				}else{
					let xValue = this[key].replace(/[^0-9]/g, '');
					if(xValue.length>maxChar){
						xValue = xValue.slice(0,maxChar);
					}
					this[key] = xValue;
				}
			},
			isFormValid(formType='required'){
				let moreChecker = {
					required: ['phone_number'],
					change_password: ['password','re_password']
				};
				for(let key in this[formType]){
					if(this[formType][key] == '' && !moreChecker[formType].includes(key)){
						this.valid[formType] = false;
						console.log(`failed check on ${key}`)
						break;
					}else if(key=='phone_number' && this.option.has_phone_number && this[formType][key] == ''){
						this.valid[formType] = false;
						console.log(`failed check on ${key}`)
						break;
					}else if(['password','re_password'].includes(key) && this.option.change_password && this[formType][key] == ''){
						this.valid[formType] = false;
						console.log(`failed check on ${key}`)
						break;
					}else{
						this.valid[formType] = true;
					}
				}
				console.log('formData:',this[formType],'Valid:',this.valid[formType])
				return this.valid[formType];
			},
		}));
		let masterContent = document.querySelector('.master-content')
		masterContent.setAttribute('x-data', 'edit_user')
	})
</script>
@endsection