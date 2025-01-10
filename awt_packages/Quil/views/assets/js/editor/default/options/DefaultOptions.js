import { options } from "../../../main.js";
import {marginOptions} from "./Margins.js";
import {paddingOptions} from "./Padding.js";
import {fontOptions} from "./Fonts.js";
import {borderOptions} from "./Border.js";
import {backgroundOptions} from "./Background.js";
import {dimensionOptions} from "./WidthHeight.js";


options.addOption(dimensionOptions);
options.addOption(marginOptions);
options.addOption(paddingOptions);
options.addOption(backgroundOptions);
options.addOption(borderOptions);
options.addOption(fontOptions);
