/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

const ImagePreview = {
	defaultOptions: {
		maxColumnGrid: 5, // Maksimal kolom dalam grid
		maxFileSize: 2 * 1024 * 1024, // 2MB
		inputName: 'file',
		height: '200px',
	},
	selectedFiles: [],
	container: '',
	fileInput: '',
	previewContainer: '',
	init: function (containerSelector, options = {}) {
		console.log('options',options)
		this.container = document.querySelector(containerSelector);
		this.fileInput = this.container.querySelector('input[type="file"]');
		this.previewContainer = this.container.querySelector('.preview-container');
		this.defaultOptions = { ...this.defaultOptions, ...options };
		const settings = this.defaultOptions;

		// Atur input file agar posisinya absolut dan inset 0 terhadap container
		this.fileInput.style.position = 'absolute';
		this.fileInput.style.top = '0';
		this.fileInput.style.left = '0';
		this.fileInput.style.right = '0';
		this.fileInput.style.bottom = '0';
		this.fileInput.style.opacity = '0'; // Sembunyikan input file

		this.container.style.height = settings.height;

		// Tambahkan event listener pada input file
		this.fileInput.addEventListener('change', (event) => {
			const files = event.target.files;
			Array.from(files).forEach(file => {
				if (file.size > settings.maxFileSize) {
					alert(`File ${file.name} terlalu besar! Maksimal ukuran file adalah ${settings.maxFileSize / 1024 / 1024} MB.`);
					return;
				}
				if (!this.selectedFiles.some(f => f.name === file.name && f.size === file.size && f.type === file.type)) {
					this.selectedFiles.push(file);
				}
			});
			this.previewContainer.innerHTML = '';
			this.render();
			console.log(this.selectedFiles);
		});
	},
	render: function () {
		const settings = this.defaultOptions;
		this.selectedFiles.forEach((file,index) => {
			const fileReader = new FileReader();
			fileReader.onload = (e) => {
				const fileType = file.type.startsWith('image/') ? 'image' : 'other';

				// Buat elemen preview
				const previewItem = document.createElement('div');
				previewItem.classList.add('preview-item');
				previewItem.style.width = `100%`;
				previewItem.style.height = 'auto';
				previewItem.style.aspectRatio = '1/1'; // Untuk aspek rasio 1:1
				previewItem.style.position = 'relative';
				previewItem.style.overflow = 'hidden';

				if (fileType === 'image') {
					const image = document.createElement('img');
					image.src = e.target.result;
					image.style.objectFit = 'cover';
					console.log('settings',settings.imageClass)
					if (settings.imageClass) {
						image.classList.add(...settings.imageClass.split(' '));
					}
					image.style.width = '100%';
					image.style.height = '100%';
					image.style.position = 'absolute';
					image.style.zIndex = '10';
					previewItem.appendChild(image);
				} else {
					const icon = document.createElement('div');
					icon.classList.add('file-icon');
					icon.style.fontSize = '24px'; // Ukuran font awesome icon
					icon.classList.add('fa', 'fa-file'); // Menambahkan ikon fontawesome
					const ext = file.name.split('.').pop().toUpperCase();
					const text = document.createElement('div');
					text.textContent = ext;
					text.style.textAlign = 'center';
					text.style.marginTop = '8px';
					previewItem.appendChild(icon);
					previewItem.appendChild(text);
				}

				previewItem.dataset.fileIndex = this.selectedFiles.length;
				previewItem.dataset.fileName = file.name;
				previewItem.dataset.fileSize = file.size;
				previewItem.dataset.fileType = file.type;

				// Tambahkan button untuk menghapus file
				const removeButton = document.createElement('button');
				removeButton.classList.add('btn', 'btn-danger','remove-file','btn-sm');
				removeButton.style.position = 'absolute';
				removeButton.style.top = '8px';
				removeButton.style.right = '8px';
				removeButton.style.zIndex = '100';
				removeButton.type = 'button';
				removeButton.innerHTML = ' <i class="fa fa-trash"></i> ';
				removeButton.addEventListener('click', (event) => {
					event.stopPropagation();
					const index = event.target.parentElement.dataset.fileIndex;
					this.selectedFiles = this.selectedFiles.filter((f, i) => i !== index);
					this.previewContainer.removeChild(event.target.closest('.preview-item'));
				});
				previewItem.appendChild(removeButton);

				// Tambahkan input[type=file] untuk preview-item
				const inputName = this.defaultOptions.inputName || 'file';
				const input = document.createElement('input');
				input.type = 'file';
				input.name = `${inputName}[]`;
				input.files = this.setFileInput([file]);
				input.style.position = 'absolute';
				input.style.top = '8px';
				input.style.left = '8px';
				input.style.right = '8px';
				input.style.bottom = '8px';
				input.style.opacity = '0';
				input.style.zIndex = '0';
				input.id = `${inputName}-${index}`;
				
				previewItem.appendChild(input);

				// Tambahkan preview-item ke dalam preview-container
				this.previewContainer.appendChild(previewItem);
			};
			fileReader.readAsDataURL(file); // Membaca file sebagai DataURL
		});
	},
	setFileInput: function (files) {
		const dataTransfer = new DataTransfer();
		files.forEach(file => dataTransfer.items.add(file));
		return dataTransfer.files;
	}
};

export default ImagePreview;

