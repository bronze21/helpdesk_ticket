@extends('layouts.dashboard',['plugins'=>['Swal2','Tinymce','lightbox']])

@section('submenu')
<div class="links h-100">
	<ul class="nav h-100">
		<li class="nav-item">
			<button role="button" class="nav-link text-white" x-on:click="showDetailTicket = !showDetailTicket">
				<i class="fa fa-filter d-block text-center" style="font-size: 1.25rem"></i>
				<small>Ticket Detail</small>
			</button>
		</li>
	</ul>
</div>
@endsection

@section('content')
<section class="container">
	<div class="card mb-4" x-show="!showDetailTicket" x-cloack>
		<div class="card-body">
			<div class="row">
				<div class="col-auto">
					<h5 class="card-title">Ticket Detail</h5>
				</div>
				<div class="col text-end">
					@if (in_array($data->status,['closed','unresolved']) && auth()->user()->role->slug=='user')
					<button type="button" class="btn btn-sm btn-outline-success">Re Open</button>
					@elseif(!in_array($data->status,['closed','unresolved']) && auth()->user()->role->slug!='user')
					<button type="button" class="btn btn-sm btn-info shadow text-white" data-bs-toggle="modal" data-bs-target="#mdl_change_status">Change Status</button>
					@endif
				</div>
			</div>
			<div class="w-100 py-2"></div>
			<div class="row">
				<div class="col-lg-4">
					<div class="row">
						<div class="col-12">
							<div class="d-flex">
								<div class="col-auto">
									<label for="" class="d-block">Requester</label>
								</div>
								<div class="col text-end">
									<strong class="d-block">{{$data->owner->name}}</strong>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="d-flex">
								<div class="col-auto">
									<label for="" class="d-block">Status | Priority</label>
								</div>
								<div class="col text-end">
									<strong class="d-block">{{ucwords(str_replace(['_','-'],' ',$data->status))}} | {{ucwords($data->priority)}}</strong>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="row">
						<div class="col-12">
							<div class="d-flex">
								<div class="col-auto">
									<label for="" class="d-block">Department</label>
								</div>
								<div class="col text-end">
									<strong class="d-block">{{$data->category->name}}</strong>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="d-flex">
								<div class="col-auto">
									<label for="" class="d-block">Layanan</label>
								</div>
								<div class="col text-end">
									<strong class="d-block">{{$data->subcategory->name}}</strong>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="row">
						<div class="col-12">
							<div class="d-flex">
								<div class="col-auto">
									<label for="" class="d-block">Tanggal Buat</label>
								</div>
								<div class="col text-end">
									<strong class="d-block">{{($data->created_at->isoFormat('ddd, DD MMM YYYY HH:mm')." WIB")}}</strong>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="d-flex">
								<div class="col-auto">
									<label for="" class="d-block">Pembaruan Terakhir</label>
								</div>
								<div class="col text-end">
									<strong class="d-block">{{$data->created_at->gte($data->latest_update) ? '-' : ($data->latest_update->isoFormat('ddd, DD MMM YYYY HH:mm')." WIB")}}</strong>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@if (!in_array($data->status,['closed','unresolved']))
	<form class="card" method="POST" action="{{route('tickets.store_reply',$data->id)}}" enctype="multipart/form-data">
		<div class="card-body" :class="{'border-bottom': !showSubCategory}">
			<div class="row">
				<div class="col align-self-center">
					<h4 class="card-title mb-0">Reply Ticket</h4>
				</div>
				<div class="col-auto align-self-center">
					<button type="button" class="btn btn-sm btn-light shadow" x-on:click="showAddAttachment=!showAddAttachment"><i class="fa fa-paperclip me-1"></i> Insert Attachment</button>
					<button type="button" class="btn btn-sm btn-light shadow" x-on:click="showSubCategory=!showSubCategory"><i class="fa me-1" :class="!showSubCategory ? 'fa-eye-slash' : 'fa-eye'"></i> <span x-text="!showSubCategory ? 'Hide Message' : 'Show Message'">Hide Message</span></button>
				</div>
			</div>
		</div>
		<div class="card-body" id="message-reply" x-show="!showSubCategory">
			@csrf
			<div class="form-group mb-3">
				<label for="message">Pesan: <small class="text-danger">*</small></label>
				<div class="float-end">
					<a href="" role="button" class="link" x-on:click="event.preventDefault();addDummyText()">Input Dummy Text</a>
				</div>
				<textarea name="message" id="message" cols="30" rows="10" class="form-control" placeholder="Please Input Your Message"></textarea>
			</div>
		</div>
		<div class="card-body" id="add-attachment" x-cloak x-show="showAddAttachment">
			<label for="attachments">Attachments: </label>
			<div id="image-preview" class="form-control">
				<input type="file" multiple id="file-input">
				<div class="preview-container"></div>
			</div>
		</div>
		<div class="card-body text-end pt-0" x-show="!showSubCategory">
			<button type="submit" class="btn btn-primary"><i class="fa fa-send me-1"></i>Send</button>
		</div>
	</form>
	@endif
	<div class="w-100 py-3"></div>
	<div class="card">
		@foreach ($data->comments as $reply)
		<div class="card-body">
			<div class="row mx-0 mb-4">
				<div class="col" style="background: rgba(0, 0, 0, 0.15);">
					<div class="d-flex align-items-center justify-content-between py-1">
						<span class="d-inline-block py-1">Posted by "{{$reply->user->name}}" at {{$reply->created_at->isoFormat('ddd, DD MMM YYYY HH:mm')}} WIB</span>
						<div class="rounded border border-dark p-1">{{$reply->user->role->name}}</div>
					</div>
				</div>
			</div>
			<div class="row mx-0 mb-3">
				<div class="col-12 px-lg-4">
					{!! $reply->messages !!}
				</div>
			</div>
			@if ($reply->attachments->count() > 0)
			<div class="row mx-0 pt-3 border-top">
				<div class="col-12">
					<span class="card-title"><i class="fa fa-paperclip me-2"></i>Attachments ({{$reply->attachments->count()}})</span>
				</div>
				<div class="col-12 px-0">
					<div id="image-preview" class="without-content attachment-gallery form-control p-2">
						<div class="preview-container">
							@foreach ($reply->attachments as $attachment)
							<div class="preview-item text-decoration-none text-dark" style="height: max-content;"" data-toggle="lightbox">
								<div class="preview-thumbnail">
									@if (str_contains($attachment->type,'image'))
									<a x-on:click="setImageViewer('{{asset("storage/$attachment->path")}}')" data-gallery="{{$reply->ticket_id}}">
										<img src="{{asset("storage/$attachment->path")}}" alt="" class="img-thumbnail square">
									</a>
									@else
									<div class="preview-icon bg-dark">
										<i class="fa fa-file text-white"></i>
									</div>
									@endif
								</div>
								<div class="preview-item-content">
									<div class="preview-item-content text-center">
										<small class="preview-item-name">
											{{$attachment->name}}
										</small>
										<small class="preview-item-size">
											{{number_format($attachment->size/1024,2)}} KB
										</small>
									</div>
								</div>
							</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
			@endif
		</div>
		@endforeach
	</div>
</section>
@endsection

@section('modal')
<div class="modal fade" id="image_viewer">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-body position-relative">
				<button type="button" class="btn btn-sm btn-secondary rounded-circle border position-absolute" style="top: 10px; right: 10px;" data-bs-dismiss="modal" aria-label="Close">
					<i class="fa fa-times fa-2x"></i>
				</button>
				<img :src="previewImage" alt="" class="img-fluid">
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="mdl_change_status">
	
	<form data-action="{{route('tickets.change_status',$data->id)}}" method="post" class="modal-dialog modal-dialog-centered" x-on:submit.prevent="changeStatus()">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Change Status</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				@csrf
				<div class="form-group">
					<label for="status">Status</label>
					<select name="status" id="status" class="form-control" x-model="status">
						<template x-for="status,key in datas">
							<option :value="key" x-text="status"></option>
						</template>
					</select>
				</div>
				<div class="w-100 py-2"></div>
				<div class="text-end">
					<button type="submit" class="btn btn-primary shadow-sm">Submit</button>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection

@section('js')
@php
	// $data_raw = $categories->map(fn($category) => (object)['name' => $category->name, 'slug' => $category->slug, 'subcategory'=>$category->subCategories->select('id','name','slug')]);
	$data_raw = collect($data->availableStatus)->mapWithKeys(function($a,$b){
		return [
			$a => ucwords(str_replace('_',' ',$a))
		];
	});
@endphp
<script>
	document.addEventListener('alpine:init', () => {
		console.log("APLINE INIT SUCCESS")
		let textEditor;
		let imgViewer = new bootstrap.Modal(document.getElementById('image_viewer'))
		Alpine.data('create_ticket', () => ({
			datas: @json($data_raw),
			subCategories: [],
			required: {
				title: '',
				category: '',
				subcategory: '',
				priority: '',
			},
			status:'',
			previewImage: '',
			showSubCategory: false,
			showAddAttachment: false,
			showDetailTicket: false,
			init() {
				this.$watch('showAddAttachment',()=>{
					console.log('showAddAttachment',this.showAddAttachment)
				})
				this.$watch('showSubCategory',()=>{
					console.log('showSubCategory',this.showSubCategory)
				})
				console.log(this.showAddAttachment,this.showSubCategory)
			},
			setShowAddAttachment(){
				this.showAddAttachment = !this.showAddAttachment	
			},
			setCategory(category){
				this.showSubCategory = !this.showSubCategory
			},
			setImageViewer(image){
				this.previewImage = image
				imgViewer.show()
			},
			changeStatus(){
				let form = document.querySelector('#mdl_change_status form')
				let action = form.dataset.action

				SwalBS.fire({
					title: "Perhatian",
					text: "Apakah anda yakin ingin mengubah status?",
					icon: "question",
					showCancelButton: true,
				}).then((result) => {
					if(result.isConfirmed){
						SwalBS.close()
						axios.post(action, {
							status: this.status
						}).then(res => {
							console.log(res)
							window.location.reload()	
						})
					}
				})
			},
			addDummyText(){
				let message = `
				<p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Fugiat error autem delectus quas earum similique animi velit voluptatem, voluptate a necessitatibus cum dolores aliquam nostrum ipsam aliquid nam reprehenderit inventore. Quo harum sit, sed asperiores ipsam odio quod et magnam facere aliquid hic consequuntur enim sint perspiciatis nulla nihil animi fuga fugit saepe tempore ab nesciunt. Perspiciatis molestiae perferendis beatae! Ad sed unde sint dolor exercitationem amet! Quod quo consequatur assumenda voluptas. Inventore cumque debitis mollitia pariatur ipsam temporibus praesentium facilis delectus? Incidunt aspernatur fugit quis! Nam eaque vero fugit? Consequuntur ea, sequi eveniet cum perspiciatis sapiente hic cumque aliquid quibusdam. Praesentium, cum recusandae alias expedita perferendis cupiditate aut commodi, delectus, voluptas optio facere labore. Minima explicabo incidunt sunt alias!</p>

				<p>Corporis temporibus labore vel optio delectus praesentium facere dignissimos ut saepe quisquam rem illo reprehenderit culpa, aliquid dolores itaque quas aliquam possimus doloremque quo odio autem unde numquam obcaecati! Corporis.
				Consequatur ad quo quas odio eaque quos sed odit libero voluptates neque velit fugiat iure ipsam, hic asperiores repudiandae? Sunt ad at cumque debitis sint aperiam nam minima explicabo quas.</p>

				<p>Laudantium assumenda officiis recusandae adipisci aspernatur corporis laboriosam aut optio, quaerat voluptates ratione sunt mollitia numquam possimus similique ipsum cum doloribus autem doloremque, iure qui! Architecto iusto inventore doloribus sapiente. Pariatur, rerum aliquam! Debitis commodi iure neque dolores nostrum quibusdam quidem saepe ipsum dolore dicta, iste cumque rem ab atque cum laudantium dolorum fugit consequuntur nisi, aut pariatur architecto explicabo. Dicta error molestiae qui velit eos provident in ratione quasi necessitatibus voluptate! Impedit libero nisi consequatur aliquid, praesentium suscipit ducimus architecto, quae itaque repudiandae sunt corporis sint sed adipisci amet.</p>

				<p>Minima nulla id impedit accusantium. Quod dolorem rem sapiente qui repellendus quibusdam soluta itaque voluptatum doloremque, aperiam, deserunt sunt rerum debitis nam. Doloribus vel quis quia ducimus vitae excepturi sed!</p>
				`
				let editor = Tinymce.get(`message`)
				console.log(editor)
				editor.insertContent(message)
			}
		}))
		let masterContent = document.querySelector('.master-content')
		masterContent.setAttribute('x-data', 'create_ticket')
		textEditor = Tinymce.init({
			license_key: 'gpl',
			selector: '#message',
			base_url: '/tinymce',
			skin: false,
			height: 200,
			menubar: false,
			skin_url: 'default',
    		content_css: 'default',
			plugins: [
				'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
				'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
				'insertdatetime', 'media', 'table', 'help', 'wordcount'
			],
			toolbar: 'undo redo | blocks | ' +
			'fontsize | bold italic underline backcolor | ' +
			'table | alignleft aligncenter ' +
			'alignright alignjustify | bullist numlist outdent indent | ' +
			'removeformat | help',
			content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
		});

		let fileUpload = ImagePreview.init('#image-preview', {
			imageClass: 'img-thumbnail'
		});
	})
</script>
@endsection