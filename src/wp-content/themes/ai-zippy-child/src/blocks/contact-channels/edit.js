import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl, ToggleControl } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { title, subtitle, placeholderText, verifiedLabel, items } = attributes;
	const blockProps = useBlockProps({ className: "cch" });
	const update = (index, patch) => setAttributes({ items: items.map((c, i) => (i === index ? { ...c, ...patch } : c)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Section" initialOpen={true}>
					<TextControl label="Anchor (id để link tới)" value={attributes.anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
					<TextControl label="Chữ khi chưa có mã QR" value={placeholderText} onChange={(v) => setAttributes({ placeholderText: v })} />
					<TextControl label="Nhãn tick xác thực (alt)" value={verifiedLabel} onChange={(v) => setAttributes({ verifiedLabel: v })} />
				</PanelBody>
				{items.map((item, index) => (
					<PanelBody key={index} title={`Thẻ #${index + 1}: ${item.title || ""}`} initialOpen={false}>
						<SelectControl
							label="Màu thẻ"
							value={item.tone || "plain"}
							options={[
								{ label: "Nổi bật (nâu ấm)", value: "featured" },
								{ label: "Cộng đồng", value: "community" },
								{ label: "Thường", value: "plain" },
							]}
							onChange={(v) => update(index, { tone: v })}
						/>
						<ToggleControl label="Hiện tick vàng xác thực" checked={!!item.verified} onChange={(v) => update(index, { verified: v })} />
						<TextControl label="Alt ảnh QR" value={item.alt || ""} onChange={(v) => update(index, { alt: v })} />
					</PanelBody>
				))}
			</InspectorControls>
			<section {...blockProps}>
				<div className="cch__wrap">
					<div className="cch__heading">
						<RichText tagName="h2" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tiêu đề" allowedFormats={[]} />
						<RichText tagName="p" value={subtitle} onChange={(v) => setAttributes({ subtitle: v })} placeholder="Mô tả ngắn" allowedFormats={[]} />
					</div>
					<div className="cch__grid">
						{items.map((item, index) => (
							<article key={index} className={`cch__card cch__card--${item.tone || "plain"}`}>
								<ImagePicker className="cch__qr" imageId={item.imageId} imageUrl={item.imageUrl} onSelect={(patch) => update(index, patch)} label={placeholderText || "+ QR"} />
								<div className="cch__copy">
									<RichText tagName="h3" value={item.title} onChange={(v) => update(index, { title: v })} placeholder="Tên kênh" allowedFormats={[]} />
									<RichText tagName="p" value={item.text} onChange={(v) => update(index, { text: v })} placeholder="Mô tả" allowedFormats={[]} />
								</div>
							</article>
						))}
					</div>
				</div>
			</section>
		</>
	);
}
