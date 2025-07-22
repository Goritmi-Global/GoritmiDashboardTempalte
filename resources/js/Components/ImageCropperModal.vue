<script setup>
import { ref } from 'vue';
import VueCropper from 'vue-cropperjs';
// import 'cropperjs/dist/cropper.css';

const props = defineProps({
  show: Boolean,
  image: String,
});

const emit = defineEmits(['close', 'cropped']);

const cropper = ref(null);
const scaleX = ref(-1);
const scaleY = ref(-1);

const cropImage = () => {
  const canvas = cropper.value.getCroppedCanvas();
  if (canvas) {
    const cropped = canvas.toDataURL('image/webp');
    emit('cropped', cropped);
    emit('close');
  }
};

const reset = () => cropper.value.reset();
const zoom = (step) => cropper.value.relativeZoom(step);
const move = (x, y) => cropper.value.move(x, y);
const rotate = (deg) => cropper.value.rotate(deg);
const flipX = () => {
  cropper.value.scaleX(scaleX.value);
  scaleX.value = -scaleX.value;
};
const flipY = () => {
  cropper.value.scaleY(scaleY.value);
  scaleY.value = -scaleY.value;
};
</script>

<template>
  <transition name="fade">
    <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-40 flex justify-end">
      <div class="w-full max-w-xl h-full bg-white shadow-lg p-5 overflow-y-auto relative">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold">Upload and Crop Image</h2>
          <button class="text-2xl font-bold text-gray-500 hover:text-gray-700" @click="$emit('close')">×</button>
        </div>

        <vue-cropper
          ref="cropper"
          :src="image"
          :aspect-ratio="1"
          :view-mode="1"
          class="w-full h-80 border rounded"
        />

        <div class="grid grid-cols-2 gap-2 mt-4">
          <button class="bg-blue-600 text-white text-sm py-1.5 px-4 rounded" @click="zoom(0.2)">Zoom In</button>
          <button class="bg-blue-600 text-white text-sm py-1.5 px-4 rounded" @click="zoom(-0.2)">Zoom Out</button>
          <button class="bg-blue-600 text-white text-sm py-1.5 px-4 rounded" @click="move(-10, 0)">Left</button>
          <button class="bg-blue-600 text-white text-sm py-1.5 px-4 rounded" @click="move(10, 0)">Right</button>
          <button class="bg-blue-600 text-white text-sm py-1.5 px-4 rounded" @click="move(0, -10)">Up</button>
          <button class="bg-blue-600 text-white text-sm py-1.5 px-4 rounded" @click="move(0, 10)">Down</button>
          <button class="bg-blue-600 text-white text-sm py-1.5 px-4 rounded" @click="rotate(90)">Rotate +90°</button>
          <button class="bg-blue-600 text-white text-sm py-1.5 px-4 rounded" @click="rotate(-90)">Rotate -90°</button>
          <button class="bg-blue-600 text-white text-sm py-1.5 px-4 rounded" @click="flipX">Flip X</button>
          <button class="bg-blue-600 text-white text-sm py-1.5 px-4 rounded" @click="flipY">Flip Y</button>
        </div>

        <div class="flex justify-end mt-6 gap-2">
          <button @click="reset" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
            Reset
          </button>
          <button @click="cropImage" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Crop & Save
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
<style scoped>
 

input[type="file"] {
    display: none;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0 5px 0;
}

.header h2 {
    margin: 0;
}

.header a {
    text-decoration: none;
    color: black;
}

.content {
    display: flex;
    justify-content: space-between;
}

.cropper-area {
    /* width: 614px; */
}

.actions {
    margin-top: 1rem;
}

.actions a,
button {
    /* display: inline-block;
    padding: 5px 15px;
    background: #0062CC;
    color: white;
    text-decoration: none;
    border-radius: 3px; */
    margin-right: 1rem;
    margin-bottom: 1rem;
}

textarea {
    width: 100%;
    height: 100px;
}

.preview-area {
    /* width: 307px; */
}

.preview-area p {
    font-size: 1.25rem;
    margin: 0;
    margin-bottom: 1rem;
}

.preview-area p:last-of-type {
    margin-top: 1rem;
}

.preview {
    width: 100%;
    height: calc(372px * (9 / 16));
    overflow: hidden;
}

.crop-placeholder {
    width: 100%;
    height: 200px;
    background: #ccc;
}

.cropped-image img {
    max-width: 100%;
}
</style>

