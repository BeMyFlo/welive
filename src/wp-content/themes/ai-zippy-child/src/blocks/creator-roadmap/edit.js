import { useState } from "@wordpress/element";
import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, Button } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";
import BulletsEditor from "../shared/BulletsEditor.js";

export default function Edit({ attributes, setAttributes }) {
	const { anchor, badge, title, titleAccent, description, tabs, footnote, linkText, linkUrl } = attributes;
	const [active, setActive] = useState(0);
	const blockProps = useBlockProps({ className: "crm" });
	const set = (key) => (val) => setAttributes({ [key]: val });
	const current = Math.min(active, Math.max(tabs.length - 1, 0));
	const update = (index, patch) => setAttributes({ tabs: tabs.map((t, i) => (i === index ? { ...t, ...patch } : t)) });
	const add = () => {
		setAttributes({ tabs: [...tabs, { label: "NGÀY", name: "Giai đoạn mới", heading: "Tiêu đề", bullets: ["Nội dung"], imageId: 0, imageUrl: "" }] });
		setActive(tabs.length);
	};
	const remove = () => {
		setAttributes({ tabs: tabs.filter((_, i) => i !== current) });
		setActive(0);
	};
	const tab = tabs[current];

	return (
		<>
			<InspectorControls>
				<PanelBody title="Section" initialOpen={true}>
					<TextControl label="Anchor (id for #links)" value={anchor} onChange={set("anchor")} />
					<TextControl label="Footer link URL" value={linkUrl} onChange={set("linkUrl")} help='Dùng "/duong-dan/" cho trang trong site.' />
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<div className="crm__container">
					<div className="crm__header">
						<div className="crm__intro">
							<span className="crm__badge"><RichText tagName="span" value={badge} onChange={set("badge")} placeholder="Badge..." allowedFormats={[]} /></span>
							<h2 className="crm__title">
								<RichText tagName="span" value={title} onChange={set("title")} placeholder="Title..." allowedFormats={[]} />
								<RichText tagName="span" className="crm__title-accent" value={titleAccent} onChange={set("titleAccent")} placeholder="Accent..." allowedFormats={[]} />
							</h2>
						</div>
						<RichText tagName="p" className="crm__desc" value={description} onChange={set("description")} placeholder="Description..." allowedFormats={[]} />
					</div>

					<div className="crm__tabs">
						{tabs.map((t, index) => (
							<div key={index} className={`crm__tab${index === current ? " is-active" : ""}`} onClick={() => setActive(index)} role="button" tabIndex={0}>
								<RichText tagName="span" className="crm__tab-label" value={t.label} onChange={(val) => update(index, { label: val })} placeholder="NGÀY..." allowedFormats={[]} />
								<RichText tagName="span" className="crm__tab-name" value={t.name} onChange={(val) => update(index, { name: val })} placeholder="Tên giai đoạn" allowedFormats={[]} />
							</div>
						))}
					</div>

					{tab && (
						<div className="crm__panels">
							<div className="crm__panel is-active">
								<div className="crm__panel-text">
									<RichText tagName="span" className="crm__panel-label" value={tab.label} onChange={(val) => update(current, { label: val })} placeholder="NGÀY..." allowedFormats={[]} />
									<RichText tagName="h3" className="crm__panel-heading" value={tab.heading} onChange={(val) => update(current, { heading: val })} placeholder="Heading" allowedFormats={[]} />
									<BulletsEditor items={tab.bullets} onChange={(val) => update(current, { bullets: val })} listClass="crm__list" />
								</div>
								<ImagePicker className="crm__panel-media" imageId={tab.imageId} imageUrl={tab.imageUrl} onSelect={(patch) => update(current, patch)} />
							</div>
						</div>
					)}
					<div style={{ display: "flex", gap: 12, marginTop: 16 }}>
						<Button variant="secondary" onClick={add}>+ Thêm giai đoạn</Button>
						{tabs.length > 1 && <Button variant="link" isDestructive onClick={remove}>Xoá giai đoạn đang chọn</Button>}
					</div>

					<div className="crm__footer">
						<RichText tagName="p" className="crm__footnote" value={footnote} onChange={set("footnote")} placeholder="Ghi chú..." allowedFormats={[]} />
						<span className="crm__link"><RichText tagName="span" value={linkText} onChange={set("linkText")} placeholder="Link..." allowedFormats={[]} /></span>
					</div>
				</div>
			</div>
		</>
	);
}
