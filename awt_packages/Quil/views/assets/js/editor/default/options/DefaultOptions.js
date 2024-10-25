import { options } from "../../../main";
import {marginOptions} from "./Margins";
import {paddingOptions} from "./Padding";
import {fontOptions} from "./Fonts";
import {borderOptions} from "./Border";
import {backgroundOptions} from "./Background";
import {dimensionOptions} from "./WidthHeight";


options.addOption(dimensionOptions);
options.addOption(marginOptions);
options.addOption(paddingOptions);
options.addOption(backgroundOptions);
options.addOption(borderOptions);
options.addOption(fontOptions);
