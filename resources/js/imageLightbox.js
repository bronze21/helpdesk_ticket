/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

class ImageLightbox {
	constructor(selector, options) {
		this.selector = selector;
		this.options = options;
		this.images = [];
		this.currentIndex = 0;

		this.init();
	}

	init() {
		const elements = document.querySelectorAll(this.selector);
		elements.forEach((element) => {
			element.addEventListener("click", (e) => {
				e.preventDefault();
				this.openLightbox(element);
			});
		});
	}

	openLightbox(element) {
		const imageSrc = element.getAttribute("href");
		const image = new Image();
		image.src = imageSrc;
		this.images.push(image);

		const lightboxContainer = document.createElement("div");
		lightboxContainer.classList.add("lightbox-container");
		document.body.appendChild(lightboxContainer);

		const lightboxImage = document.createElement("img");
		lightboxImage.src = imageSrc;
		lightboxContainer.appendChild(lightboxImage);

		const closeButton = document.createElement("button");
		closeButton.textContent = "Close";
		closeButton.addEventListener("click", () => {
			this.closeLightbox();
		});
		lightboxContainer.appendChild(closeButton);

		if (this.options.showGallery) {
			const galleryContainer = document.createElement("div");
			galleryContainer.classList.add("gallery-container");
			lightboxContainer.appendChild(galleryContainer);

			this.images.forEach((image, index) => {
				const galleryImage = document.createElement("img");
				galleryImage.src = image.src;
				galleryImage.addEventListener("click", () => {
					this.currentIndex = index;
					this.updateLightboxImage();
				});
				galleryContainer.appendChild(galleryImage);
			});
		}

		this.updateLightboxImage();
	}

	closeLightbox() {
		const lightboxContainer = document.querySelector(".lightbox-container");
		lightboxContainer.remove();
	}

	updateLightboxImage() {
		const lightboxImage = document.querySelector(".lightbox-container img");
		lightboxImage.src = this.images[this.currentIndex].src;
	}
}

export default ImageLightbox