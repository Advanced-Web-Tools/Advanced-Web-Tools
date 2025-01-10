import {Block} from "../../../Quil/views/assets/js/editor/blocks/Block.js";
import {blocks, options} from "../../../Quil/views/assets/js/main.js";
import {BackgroundImage, ImageOption} from "./MediaOptions.js";


const img = new Block();
img.setName("Image");
img.setCategory("Media");
img.setFaIcon("fa-solid fa-image");
img.addBody("<div class='block' data-media='image' ><img src='https://www.svgrepo.com/show/508699/landscape-placeholder.svg' style='width: 300px; height: auto; margin: auto;' alt='Image' /></div>");

const video = new Block();
video.setName("Video");
video.setCategory("Media");
video.setFaIcon("fa-solid fa-video");
video.addBody("<div  data-media='video' class='block' width='250px' height='250px' ><video width='100%' height='100%' controls><source src='' type='video/mp4'><source src='' type='video/avi'><source src='' type='video/webm'>Your browser does not support the video tag.</video></div>");

blocks.addBlock(img);
blocks.addBlock(video);


options.addOption(ImageOption);
options.addOption(BackgroundImage);