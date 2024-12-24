{{--
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
--}}

@extends('layouts.dashboard',['plugins'=>['DataTables','Swal2']])


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
<section class="container shadow bg-white mb-3">
	<div class="row justify-content-md-center h-100">
		<div class="col-12 px-md-4">
			<form action="{{route('categories.update',$data->id)}}" method="post"
				class="main-form position-relative h-100" x-watch="isFormValid()">
				<div class="form-group row mb-3 mx-0">
					@csrf
					@method('PUT')
					<label class="col-lg-4 ps-0 col-form-label" for="name">Nama <small
							class="text-danger">*</small></label>
					<input type="text" name="name" id="name" class="form-control col-lg" required="required"
						x-model="category.required.name" value="{{$data?->name ?? ''}}">
					@error('name')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

				<div class="w-100 clearfix py-5"></div>
				<div class="row position-absolute w-100 mx-0 absolute" style="bottom: 0;">
					<div class="col-md-6 offset-md-6 px-0">
						<button type="submit"
							class="btn btn-primary d-flex w-100 justify-content-center align-items-center"
							:disabled="!isFormValid()">
							<i class="fa fa-save me-1"></i>Save
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
<section class="container bg-white shadow">
	<div class="row mb-3">
		<div class="col">
			<h5 class="fw-bold">Sub Categories</h5>
		</div>
		<div class="col-auto">
			<input type="hidden" id="category_id" value="{{$data->id}}">
			<button type="btn" class="btn btn-primary" data-bs-toggle="modal"
				data-bs-target="#mdl_create_subcategory"><i class="fa fa-plus me-1"></i>Sub Category</button>
		</div>
	</div>
	<div class="row">
		<div class="col-12 px-md-4">
			<div class="table-responsive">
				<table class="table table-borderless datatables" id="subcategories"
					data-ajax_url="{{route('categories.subcategories', $data->id)}}">
					<thead>
						<tr>
							<th data-col_name="DT_RowIndex" data-orderable="true" width="5%" scope="col">#</th>
							<th data-col_name="name" data-orderable="true" scope="col">Sub Category Name</th>
							<th data-col_name="status" data-orderable="true" scope="col">Status</th>
							<th data-col_name="action" data-orderable="false" scope="col" class="text-end">Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</section>
@endsection

@section('modal')
<div class="modal fade" id="mdl_create_subcategory">
	<form action="{{route('subcategories.store')}}" method="post" class="modal-dialog modal-dialog-centered"
		x-on:submit.prevent="submitSubcategory($el,'create_subcategory')">
		@csrf
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Create Sub Category</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				@csrf
				<div class="form-group row">
					<label class="col-auto col-form-label" for="name">Sub Category Name <small
							class="text-danger">*</small></label>
					<div class="col-lg">
						<input type="hidden" name="category_id" x-model="category_id">
						<input type="text" name="name" id="name" class="form-control" required="required"
							x-model="create_subcategory.required.name">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary" :disabled="!isFormValid('create_subcategory')"><i
						class="fa fa-save me-1"></i><span>Save</span></button>
			</div>
		</div>
	</form>
</div>
<div class="modal fade" id="mdl_edit_subcategory">
	<form :action="edit_subcategory.action" method="post" class="modal-dialog modal-dialog-centered" x-on:submit.prevent="submitSubcategory($el,'edit_subcategory')">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Sub Category</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				@csrf
				<div class="form-group row">
					<label class="col-auto col-form-label" for="name">Sub Category Name <small
							class="text-danger">*</small></label>
					<div class="col-lg">
						<input type="hidden" name="category_id" x-model="category_id">
						<input type="text" name="name" id="name" class="form-control" required="required"
							x-model="edit_subcategory.required.name">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary" :disabled="!isFormValid('edit_subcategory')"><i
						class="fa fa-save me-1"></i><span>Save</span></button>
			</div>
		</div>
	</form>
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
				let tableName = modal.querySelector('.datatables')
				if (tableName) {
					tableName = tableName.getAttribute('id')
					DTables[tableName].ajax.reload();
				}
			})
			
		})
		console.log(DTables)
		return DTables;
	}
	document.addEventListener('alpine:init', () => {
		initDTables();
		console.log("APLINE INIT SUCCESS")
		Alpine.data('edit_role', () => ({
			csrf: document.querySelector('meta[name="csrf-token"]').content,
			category_id: 0,
			option: {
				has_phone_number: true
			},
			category: {
				required: {
					name: '',
				},
				valid: false,
			},
			create_subcategory: {
				required: {
					name: '',
				},
				valid: false,
			},
			edit_subcategory: {
				action: '',
				data: {},
				required: {
					name: '',
				},
				valid: false,
			},
			init() {
				console.log('category_id',document.getElementById('category_id').value)
				this.category_id = document.getElementById('category_id').value
				this.category.required = {
					name: document.getElementById('name').value,
				}
				this.$watch('edit_subcategory', () => {
					console.log('edit subcategory changed',this.edit_subcategory)
				})
			},
			getSubcategory(el,id){
				console.log('get subcategory',{el,id});
				let that = this
				let mdlEl = document.getElementById('mdl_edit_subcategory')
				const modalInstance = new bootstrap.Modal(mdlEl);
				console.log(mdlEl,modalInstance);
				axios.get(el.dataset.url).then(res => {
					this.edit_subcategory.data = res.data.data
					this.edit_subcategory.required.name = res.data.data.name
					this.edit_subcategory.action = this.edit_subcategory.data.update_url
					modalInstance.show()
				})	
			},
			submitSubcategory(el,formType){
				let url = el.getAttribute('action')
				let method = "post"
				let text = "Apakah anda yakin ingin menambahkan sub kategori?"
				if(formType.includes('edit')){
					text = "Apakah anda yakin ingin mengubah sub kategori?"
					method = "put"
				}
				
				SwalBS.fire({
					title: 'Perhatian',
					text: text,
					icon: 'question',
					showCancelButton: true,
					confirmButtonText: 'Ya',
					cancelButtonText: 'Tidak'
				}).then((result) => {
					if(result.isConfirmed){
						SwalBS.close()
						SwalBS.showLoading();
						axios[method](url,{
							category_id: this.category_id,
							name: this[formType].required.name,
						}).then(res => {
							SwalBS.close()
							let mdlEl = document.getElementById(`mdl_${formType}`)
							let mdlInstance = bootstrap.Modal.getInstance(mdlEl)
							mdlInstance.hide()
							DTables['subcategories'].ajax.reload();
						}).catch(err => {
							SwalBS.close()
						})
					}
				})
			},
			deleteSubcategory(el, id){
				let url = el.getAttribute('data-url')
				SwalBS.fire({
					title: 'Perhatian',
					text: "Apakah anda yakin ingin menghapus sub kategori?",
					icon: 'question',
					showCancelButton: true,
					confirmButtonText: 'Ya',
					cancelButtonText: 'Tidak'
				}).then((result) => {
					if(result.isConfirmed){
						SwalBS.close()
						SwalBS.showLoading();
						axios.delete(url).then(res => {
							SwalBS.close()
							DTables['subcategories'].ajax.reload();
						}).catch(err => {
							SwalBS.close()
						})
					}
				})
			},
			isFormValid(formType='category'){
				console.log('validation ',this[formType].required)
				for(let key in this[formType].required){
					if(this[formType].required[key] == '' && key!='phone_number'){
						this[formType].valid = false;
						break;
					}else if(key=='phone_number' && this.option.has_phone_number && this[formType].required[key] == ''){
						this[formType].valid = false;
						break;
					}else{
						this[formType].valid = true;
					}
				}
				// console.log('formData:',this.required,'Valid:',this[formType].valid)
				return this[formType].valid;
			},
		}));
		let masterContent = document.querySelector('.master-content')
		masterContent.setAttribute('x-data', 'edit_role')
	})
</script>
@endsection