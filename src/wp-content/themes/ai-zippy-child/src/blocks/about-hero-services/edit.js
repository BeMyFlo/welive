import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, Button } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { eyebrow, title, description, btnText, btnUrl, services = [], stats = [] } = attributes;
	const blockProps = useBlockProps({ className: "ahs" });
	const setService = (i, patch) => setAttributes({ services: services.map((s, k) => (k === i ? { ...s, ...patch } : s)) });
	const setStat = (i, patch) => setAttributes({ stats: stats.map((s, k) => (k === i ? { ...s, ...patch } : s)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Nút" initialOpen={true}>
					<TextControl label="Link" value={btnUrl} onChange={(v) => setAttributes({ btnUrl: v })} />
				</PanelBody>
				<PanelBody title="Card dịch vụ" initialOpen={false}>
					{services.map((s, i) => (
						<div key={i} style={{ marginBottom: 14 }}>
							<TextControl label={`Nhãn ảnh ${i + 1}`} value={s.tag || ""} onChange={(v) => setService(i, { tag: v })} />
							<TextControl label="Alt ảnh" value={s.alt || ""} onChange={(v) => setService(i, { alt: v })} />
						</div>
					))}
					<Button variant="secondary" onClick={() => setAttributes({ services: [...services, { tag: "", title: "", text: "", alt: "", imageId: 0, imageUrl: "" }] })}>
						+ Thêm card
					</Button>
					{services.length > 0 && (
						<Button isDestructive variant="link" style={{ marginLeft: 12 }} onClick={() => setAttributes({ services: services.slice(0, -1) })}>
							Xoá card cuối
						</Button>
					)}
				</PanelBody>
				<PanelBody title="Số liệu" initialOpen={false}>
					<Button variant="secondary" onClick={() => setAttributes({ stats: [...stats, { value: "", label: "" }] })}>
						+ Thêm số liệu
					</Button>
					{stats.length > 0 && (
						<Button isDestructive variant="link" style={{ marginLeft: 12 }} onClick={() => setAttributes({ stats: stats.slice(0, -1) })}>
							Xoá số cuối
						</Button>
					)}
				</PanelBody>
			</InspectorControls>

			<section {...blockProps}>
				<div className="ahs__wrap">
					<div className="ahs__main">
						<div className="ahs__copy">
							<RichText tagName="p" className="ahs__eyebrow" value={eyebrow} onChange={(v) => setAttributes({ eyebrow: v })} placeholder="Nhãn" allowedFormats={[]} />
							<RichText tagName="h1" className="ahs__title" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tiêu đề" allowedFormats={[]} />
							<RichText tagName="p" className="ahs__desc" value={description} onChange={(v) => setAttributes({ description: v })} placeholder="Mô tả" />
							<span className="ahs__button-mock">
								<RichText tagName="span" value={btnText} onChange={(v) => setAttributes({ btnText: v })} placeholder="Nút" allowedFormats={[]} /> ↗︎
							</span>
						</div>
						<div className="ahs__services">
							{services.map((s, i) => (
								<article className="ahs__service" key={i}>
									<div className="ahs__photo">
										<ImagePicker imageId={s.imageId} imageUrl={s.imageUrl} onSelect={(v) => setService(i, v)} />
									</div>
									<div className="ahs__service-copy">
										<RichText tagName="h2" value={s.title} onChange={(v) => setService(i, { title: v })} placeholder="Tiêu đề" allowedFormats={[]} />
										<RichText tagName="p" value={s.text} onChange={(v) => setService(i, { text: v })} placeholder="Mô tả" allowedFormats={[]} />
									</div>
								</article>
							))}
						</div>
					</div>
					<div className="ahs__stats">
						{stats.map((st, i) => (
							<div key={i}>
								<RichText tagName="strong" value={st.value} onChange={(v) => setStat(i, { value: v })} placeholder="500+" allowedFormats={[]} />
								<RichText tagName="p" value={st.label} onChange={(v) => setStat(i, { label: v })} placeholder="Nhãn" allowedFormats={[]} />
							</div>
						))}
					</div>
				</div>
			</section>
		</>
	);
}
