{{--
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
--}}

@extends('layouts.dashboard',['plugins'=>['DataTables','Swal2']])

@section('submenu')
<div class="links h-100">
	<ul class="nav h-100">
		<li class="nav-item" :class="{'active': filter.isShow}">
			<button role="button" class="nav-link text-dark" x-on:click="filter.isShow = !filter.isShow">
				<i class="fa fa-filter d-block text-center" style="font-size: 1.25rem"></i>
				<small>Filter</small>
			</button>
		</li>
		<li class="nav-item">
			<a href="{{route('staff.create')}}" class="nav-link text-dark">
				<i class="fa fa-plus d-block text-center" style="font-size: 1.25rem"></i>
				<small>Create new</small>
			</a>
		</li>
	</ul>
</div>
@endsection

@section('content')
<section class="container-fluid h-100 bg-white shadow">
	<div class="row">
		<div class="col-12">
			<form method="get" action="" class="ajs-fade pb-3 filters" 
				x-show="filter.isShow" 
				x-transition:enter="ajs-fade ajs-fade-enter"
				x-transition:enter-start="ajs-fade ajs-fade-enter-start"
				x-transition:enter-end="ajs-fade ajs-fade-enter-end"
				x-transition:leave="ajs-fade ajs-fade-leave"
				x-transition:leave-start="ajs-fade ajs-fade-leave-start"
				x-transition:leave-end="ajs-fade ajs-fade-leave-end"
			>
				<div class="card">
					<div class="card-body">
						<h5><i class="fa fa-filter me-1"></i>Filter</h5>
						<div class="form-group row mb-3">
							<label for="search" class="col-md-3 align-self-center">Search: </label>
							<div class="col">
								<input type="text" name="search" id="search" class="form-control" x-model="filter.search">
							</div>
						</div>
						<div class="form-group row mb-3">
							<label for="role" class="col-md-3 align-self-center">Role: </label>
							<div class="col">
								<select name="role" id="role" class="form-control">
									<option value="all" selected>Semua Role</option>
									@foreach ($roles as $role)
									<option value="{{$role->slug}}">{{$role->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group row mb-3">
							<label for="created_date" class="col-md-3">Created Date: </label>
							<div class="col">
								<div class="input-group">
									<input type="date" name="date_start" id="date_start" class="form-control" x-model="filter.date.start"> 
									<span class="input-group-text">to</span>
									<input type="date" name="date_end" id="date_end" class="form-control" x-model="filter.date.end"> 
								</div>
							</div>
						</div>
						<div class="text-end">
							<button type="button" class="btn btn-outline-primary"><i class="fa fa-refresh me-1" x-on:click="resetFilter()"></i>Reset</button>
							<button type="submit" class="btn btn-primary"><i class="fa fa-filter me-1"></i>Filter</button>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="table-responsive">
						<table class="mb-0 table table-borderless" id="data_table">
							<thead>
								<tr>
									<th scope="col">No.</th>
									<th scope="col">Nama</th>
									<th scope="col">Email</th>
									<th scope="col">No. Telepon</th>
									<th scope="col" class="text-end">Role</th>
									<th scope="col" class="text-end">Created Date</th>
									<th scope="col" class="text-end">Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($datas as $data)
								<tr>
									<td>{{$loop->iteration}}</td>
									<td>{{$data->name}}</td>
									<td>{{$data->email}}</td>
									<td>{{$data?->phone_number ?? '-'}}</td>
									<td class="text-end">
										@if ($data?->role?->name)
											<span class="badge bg-info d-block">{{$data->role->name}}</span>
										@endif
									</td>
									<td class="text-end">{{$data->created_at->isoFormat('ddd, DD MMM YYYY HH:mm')}} WIB</td>
									<td class="text-end">
										<a href="{{route('staff.edit', $data->id)}}" role="button" class="btn btn-sm btn-outline-dark">
											<i class="fa fa-edit me-0 me-lg-2"></i>
											<span class="d-none d-lg-inline-block">Edit</span>
										</a>
										<a href="{{route('staff.destroy', $data->id)}}" role="button" class="btn btn-sm btn-outline-danger" x-on:click.prevent="confirmDelete($el, {{$data->id}})">
											<i class="fa fa-trash me-0 me-lg-2"></i>
											<span class="d-none d-lg-inline-block">Delete</span>
										</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					{{$datas->links()}}
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@section('alpinejs')
<script>
	document.addEventListener('alpine:init', () => {
		let table = new DataTables('#data_table',{
			searching:false
		})
		console.log("APLINE INIT SUCCESS")
		Alpine.data('staff', () => ({
			filter:{
				search: '',
				date: {
					start:'',
					end:'',
				},
				isShow: true,
			},
			init() {
				this.$watch('filter.isShow', () => {
					console.log('isShow',this.filter.isShow)
				})
				console.log("FILTER INIT SUCCESS, Show:",this.filter.isShow)
			},
			resetFilter(){
				this.filter.search = ''
				this.filter.date.start = ''
				this.filter.date.end = ''
			},
			confirmDelete(el, id){
				let url = el.getAttribute('href')
				SwalBS.fire({
					title: 'Are you sure?',
					text: "You won't be able to revert this!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.isConfirmed) {
						SwalBS.close()
						axios.delete(url).then(res => {
							SwalBS.close()
							SwalBS.fire(
								'Deleted!',
								'This account has been deleted.',
								'success'
							)
							location.reload()
						}).catch(err => {
							console.log(err)
							SwalBS.close()
							SwalBS.fire(
								'Failed!',
								'This account has not been deleted.',
								'error'
							)
						})
					}
				})
			}
		}))
		let masterContent = document.querySelector('.master-content')
		masterContent.setAttribute('x-data', 'staff')
	})
</script>
@endsection