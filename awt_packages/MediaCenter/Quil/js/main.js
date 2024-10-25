import {Block} from "../../../Quil/views/assets/js/editor/blocks/Block";
import {blocks, options} from "../../../Quil/views/assets/js/main";
import {BackgroundImage, ImageOption} from "./MediaOptions";


const img = new Block();
img.setName("Image");
img.setCategory("Media");
img.setFaIcon("fa-solid fa-image");
img.addBody("<div class='block'><img class='block' src='https://www.svgrepo.com/show/508699/landscape-placeholder.svg' style='width: 300px; height: auto; margin: auto;' alt='Image' /></div>");

const video = new Block();
video.setName("Video");
video.setCategory("Media");
video.setFaIcon("fa-solid fa-video");
video.addBody("<video class='block' controls><source src='' type='video/mp4'>Your browser does not support the video tag.</video>");

blocks.addBlock(img);
blocks.addBlock(video);


options.addOption(ImageOption);
options.addOption(BackgroundImage);