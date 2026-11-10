import { registerBlockType } from "@wordpress/blocks";
import save from "./save.js";
import Edit from "./edit.js";
import metadata from "./block.json";
import "./style.scss";
import "./editor.scss";

registerBlockType(metadata.name, {
	save,
	edit: Edit,
});
