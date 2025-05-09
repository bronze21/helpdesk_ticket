{{--
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
--}}

@extends('layouts.dashboard',['plugins'=>['DataTables','Swal2']])


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
			<a href="{{route('roles.index')}}" class="nav-link text-dark">
				<i class="fa fa-ban d-block text-center" style="font-size: 1.25rem"></i>
				<small>Cancel</small>
			</a>
		</li>
	</ul>
</div>
@endsection

@section('content')
<section class="container-fluid shadow bg-white mb-3">
	<div class="card">
		<div class="card-body">
			<div class="row justify-content-md-center h-100">
				<div class="col-12">
					<form action="{{route('roles.update',$data->id)}}" method="post" class="main-form position-relative h-100" x-watch="isFormValid()">
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
						
						<div class="w-100 clearfix py-5"></div>
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
		</div>
	</div>
</section>
<section class="container-fluid bg-white shadow">
	<div class="card">
		<div class="card-body">
			<div class="row mb-3">
				<div class="col">
					<h5 class="fw-bold">Attach Users</h5>
				</div>
				<div class="col-auto">
					<button type="btn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mdl_attach_user"><i class="fa fa-plus me-1"></i>Attach</button>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="table-responsive">
						<table class="table table-borderless datatables" id="unassign_user" data-ajax_url="{{route('roles.users', $data->id)}}">
							<thead>
								<tr>
									<th data-col_name="DT_RowIndex" data-orderable="true" width="5%" scope="col">#</th>
									<th data-col_name="name" data-orderable="true" scope="col">User Name</th>
									<th data-col_name="unassignRole" data-orderable="false" scope="col" class="text-end">Action</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
@section('modal')
<div class="modal fade" id="mdl_attach_user">
	<div class="modal-dialog modal-dialog-centered">
		<form class="modal-content" action="#" method="post" x-on:submit.prevent="assignUsers()">
			<div class="modal-header">
				<h5 class="modal-title">Attach Users</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				@csrf
				<div class="form-group row">
					<div class="col">
						<div class="table-responsive">
							<table class="table-table-borderless datatables" id="assign_user" data-ajax_url="{{route('users.without_role')}}">
								<thead>
									<tr>
										<th data-col_name="DT_RowIndex" data-orderable="true" width="5%" scope="col">#</th>
										<th data-col_name="name" data-orderable="true" scope="col">User Name</th>
										<th data-col_name="assignRole" data-orderable="false" scope="col" class="text-end">Action</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary" :disabled="hasCheckedUsers()">Assign Selected Users</button>
			</div>
		</form>
	</div>
</div>
@endsection
@section('js')
<script>
	let DTables = [];
	function initDTables() {
		let tables = document.querySelectorAll('.datatables')
		tables.forEach(table => {
			let tableName = table.getAttribute('id')
			let ajaxUrl = table.getAttribute('data-ajax_url')
			let dtColumns = [];
			let dtColumnDefs = [];
			table.querySelectorAll('thead th').forEach(th => {
				if (th.hasAttribute('data-col_name')) {
					let orderable = th.hasAttribute('data-orderable') ? (th.getAttribute('data-orderable')=='true') : false;
					let colName = th.getAttribute('data-col_name');
					dtColumns.push({data: colName, name: colName, orderable: orderable, searchable: orderable});
				}
			})
			let lastData = dtColumns.length - 1;
			dtColumnDefs.push({
				"targets": lastData,
				"className": "text-end"
			});
			console.log(tableName,dtColumns,dtColumnDefs)
			DTables[tableName] = new DataTables(`#${tableName}`, {
				ajax: ajaxUrl,
				columns: dtColumns,
				columnDefs: dtColumnDefs ?? []
			});
		})
		let modals = document.querySelectorAll('.modal')
		modals.forEach(modal => {
			let modalId = modal.getAttribute('id')
			modal.addEventListener('shown.bs.modal', () => {
				let tableName = modal.querySelector('.datatables').getAttribute('id')
				DTables[tableName].ajax.reload();
			})
			
		})
		console.log(DTables)
		return DTables;
	}
	document.addEventListener('alpine:init', () => {
		initDTables();
		let table = new DataTables('#data_table',{
			ajax: "{{route('users.without_role')}}",
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex'},
				{data: 'name', name: 'name'},
				{data: 'assignRole', name: 'action', orderable: false, searchable: false},
			],
			columnDefs: [
				{
					"targets": 2,
					"className": "text-end"
				}
			]
			// 	url: "{{route('users.without_role')}}",
			// 	type: 'GET'
			// }
		});
		console.log("APLINE INIT SUCCESS")
		Alpine.data('edit_role', () => ({
			option: {
				has_phone_number: true
			},
			required: {
				name: '',
			},
			roles: {
				assignedUsers: {}
			},
			valid: false,
			init() {
				this.required = {
					name: document.getElementById('name').value,
				}
				this.$watch('roles.assignedUsers', () => {
					console.log('assignedUsers',this.roles.assignedUsers)
				})
			},
			assignRole(el,user_id){
				console.log({el,user_id})
				this.roles.assignedUsers[user_id] = this.roles.assignedUsers[user_id]? false: true;
				if(this.roles.assignedUsers[user_id]){
					el.innerText = "Role Assigned"
					el.classList.remove('btn-outline-dark')
					el.classList.add('btn-secondary')
					
				}else{
					el.innerText = "Assign Role"
					el.classList.add('btn-outline-dark')
					el.classList.remove('btn-secondary')
				}
			},
			unassignRole(el,user_id){
				SwalBS.fire({
					title: "Perhatian",
					text: "Apakah anda yakin untuk melepas akun ini dari role ini?",
					icon: "question",
					showCancelButton: true,
				}).then((result) => {
					if(result.isConfirmed){
						SwalBS.close()
						SwalBS.showLoading();
						el.innerText = "Loading..."
						el.disabled  = true
						axios.post("{{route('roles.detach_users',$data->id)}}",{
							users: [user_id]
						}).then(res => {
							DTables['unassign_user'].ajax.reload()	
							DTables['assign_user'].ajax.reload()	
						})
					}
				})
			},
			hasCheckedUsers(){
				let count = 0;
				for(let key in this.roles.assignedUsers){
					if(this.roles.assignedUsers[key] && this.roles.assignedUsers[key]!=false){
						count++;
					}
				}
				return count==0;
			},
			assignUsers(){
				let assignedUsers = Object.keys(this.roles.assignedUsers).filter(key => this.roles.assignedUsers[key])
				console.log('assignedUsers',assignedUsers)
				SwalBS.fire({
					title: "Perhatian",
					text: "Apakah anda yakin untuk menyimpan data?",
					icon: "warning",
					showCancelButton: true,
					confirmButtonText: 'Ya',
					cancelButtonText: 'Tidak'
				}).then((result) => {
					if(result.isConfirmed){
						SwalBS.close()
						SwalBS.showLoading();
						axios.post("{{route('roles.attach_users',$data->id)}}",{
							users: assignedUsers
						}).then(res => {
							DTables['unassign_user'].ajax.reload()
							DTables['assign_user'].ajax.reload()
							let modal = document.getElementById('mdl_attach_user')
							const modalInstance = bootstrap.Modal.getInstance(modal);
    						modalInstance.hide();
						}).catch(err => {
							console.log(err)
						})
					}
				})
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
		masterContent.setAttribute('x-data', 'edit_role')
	})
</script>
@endsection