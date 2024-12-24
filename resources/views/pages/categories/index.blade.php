@extends('layouts.dashboard',['plugins'=>['DataTables','Swal2']])

@section('submenu')
<div class="links h-100">
	<ul class="nav h-100">
		<li class="nav-item" :class="{'active': filter.isShow}">
			<button role="button" class="nav-link text-white" x-on:click="filter.isShow = !filter.isShow">
				<i class="fa fa-filter d-block text-center" style="font-size: 1.25rem"></i>
				<small>Filter</small>
			</button>
		</li>
		<li class="nav-item">
			<a href="{{route('categories.create')}}" class="nav-link text-white">
				<i class="fa fa-plus d-block text-center" style="font-size: 1.25rem"></i>
				<small>Create new</small>
			</a>
		</li>
	</ul>
</div>
@endsection

@section('content')
<section class="container h-100 bg-white shadow">
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
							<button type="button" class="btn btn-sm btn-outline-primary"><i class="fa fa-refresh me-1" x-on:click="resetFilter()"></i>Reset</button>
							<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-filter me-1"></i>Filter</button>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="col-12">
			<div class="table-responsive">
				<table class="mb-0 table table-borderless" id="data_table">
					<thead>
						<tr>
							<th scope="col">No.</th>
							<th scope="col">Nama</th>
							<th scope="col">Slug</th>
							<th scope="col" class="text-end"># Sub Categories</th>
							<th scope="col" class="text-end">Created Date</th>
							<th scope="col" class="text-end">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($datas as $data)
						<tr>
							<td>{{$loop->iteration}}</td>
							<td>{{$data->name}}</td>
							<td>{{$data->slug}}</td>
							<td class="text-end">{{$data->subCategories->count() ?? 0}}</td>
							<td class="text-end">{{$data->created_at->isoFormat('ddd, DD MMM YYYY HH:mm')}} WIB</td>
							<td class="text-end">
								<a href="{{route('categories.edit', $data->id)}}" role="button" class="btn btn-sm btn-outline-dark">
									<i class="fa fa-edit me-0 me-lg-2"></i>
									<span class="d-none d-lg-inline-block">Edit</span>
								</a>
								<button type="button" data-url="{{route('categories.destroy', $data->id)}}" class="btn btn-sm btn-outline-danger" x-on:click="confirmDelete({{$data->id}})">
									<i class="fa fa-trash me-0 me-lg-2"></i>
									<span class="d-none d-lg-inline-block">Delete</span>
								</button>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			{{$datas->links()}}
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
		Alpine.data('categories', () => ({
			filter:{
				search: '',
				date: {
					start:'',
					end:'',
				},
				isShow: true,
			},
			init() {
				if(window.matchMedia("(orientation: portrait) and (max-width: 570px)").matches){
					this.filter.isShow = false;
				}
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
			confirmDelete(id){
				SwalBS.fire({
					title: 'Perhatian',
					text: 'Apakah anda yakin untuk menghapus data?',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Ya',
					cancelButtonText: 'Tidak'
				}).then((result) => {
					if(result.isConfirmed){
						SwalBS.close()
						SwalBS.showLoading();
						axios.delete("{{route('categories.destroy',':id')}}".replace(':id',id)).then(res => {
							SwalBS.close()
							window.location.reload();
						}).catch(err => {
							SwalBS.close()
							console.log(err)
						})
					}
				})
			}
		}))
		let masterContent = document.querySelector('.master-content')
		masterContent.setAttribute('x-data', 'categories')
	})
</script>
@endsection