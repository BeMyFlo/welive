import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, Button } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { title, titleAccent, lead, text, ctaText, ctaUrl, images = [], metrics = [] } = attributes;
	const blockProps = useBlockProps({ className: "hab" });

	const setImage = (i, patch) => setAttributes({ images: images.map((img, k) => (k === i ? { ...img, ...patch } : img)) });
	const setMetric = (i, patch) => setAttributes({ metrics: metrics.map((m, k) => (k === i ? { ...m, ...patch } : m)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Nút" initialOpen={true}>
					<TextControl label="Link" value={ctaUrl} onChange={(v) => setAttributes({ ctaUrl: v })} help="Ví dụ /about/ hoặc https://..." />
				</PanelBody>
				<PanelBody title="Alt ảnh" initialOpen={false}>
					{images.map((img, i) => (
						<TextControl key={i} label={`Ảnh ${i + 1}`} value={img.alt || ""} onChange={(v) => setImage(i, { alt: v })} />
					))}
				</PanelBody>
				<PanelBody title="Số liệu" initialOpen={false}>
					<Button variant="secondary" onClick={() => setAttributes({ metrics: [...metrics, { value: "", label: "" }] })}>
						+ Thêm số liệu
					</Button>
					{metrics.length > 0 && (
						<Button isDestructive variant="link" style={{ marginLeft: 12 }} onClick={() => setAttributes({ metrics: metrics.slice(0, -1) })}>
							Xoá số cuối
						</Button>
					)}
				</PanelBody>
			</InspectorControls>

			<section {...blockProps}>
				<div className="hab__wrap">
					<div className="hab__intro">
						<div className="hab__collage">
							{images.slice(0, 3).map((img, i) => (
								<ImagePicker
									key={i}
									className={i === 0 ? "hab__collage-main" : ""}
									imageId={img.imageId}
									imageUrl={img.imageUrl}
									onSelect={(v) => setImage(i, v)}
									label={`+ Ảnh ${i + 1}`}
								/>
							))}
						</div>
						<div className="hab__copy">
							<h2 className="hab__title">
								<RichText tagName="span" style={{ color: "inherit" }} value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Về" />{" "}
								<RichText tagName="span" value={titleAccent} onChange={(v) => setAttributes({ titleAccent: v })} placeholder="Welive." />
							</h2>
							<RichText tagName="p" className="hab__lead" value={lead} onChange={(v) => setAttributes({ lead: v })} placeholder="Câu nhấn" />
							<RichText tagName="p" className="hab__text" value={text} onChange={(v) => setAttributes({ text: v })} placeholder="Mô tả" />
							<span className="hab__pill-mock">
								<RichText tagName="span" value={ctaText} onChange={(v) => setAttributes({ ctaText: v })} placeholder="Nút" allowedFormats={[]} /> ↗
							</span>
						</div>
					</div>
					<div className="hab__metrics">
						{metrics.map((m, i) => (
							<div key={i} className="hab__metrics-edit">
								<RichText tagName="strong" value={m.value} onChange={(v) => setMetric(i, { value: v })} placeholder="500+" allowedFormats={[]} />
								<RichText tagName="p" value={m.label} onChange={(v) => setMetric(i, { label: v })} placeholder="Nhãn" allowedFormats={[]} />
							</div>
						))}
					</div>
				</div>
			</section>
		</>
	);
}
