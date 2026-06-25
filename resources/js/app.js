import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'

import Cropper from 'cropperjs';


Alpine.plugin(collapse)




window.Alpine = Alpine;
window.Cropper = Cropper;

Alpine.start();