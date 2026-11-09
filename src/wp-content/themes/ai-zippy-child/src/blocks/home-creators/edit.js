import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, Button } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { title, titleAccent, linkText, linkUrl, creators = [] } = attributes;
	const blockProps = useBlockProps({ className: "hcr" });
	const setItem = (i, patch) => setAttributes({ creators: creators.map((c, k) => (k === i ? { ...c, ...patch } : c)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Link 'Xem tất cả'" initialOpen={true}>
					<TextControl label="Link" value={linkUrl} onChange={(v) => setAttributes({ linkUrl: v })} />
				</PanelBody>
				<PanelBody title="Link & vị trí ảnh từng người" initialOpen={false}>
					{creators.map((c, i) => (
						<div key={i} style={{ marginBottom: 16 }}>
							<strong>{c.name || `Creator ${i + 1}`}</strong>
							<TextControl label="Link" value={c.url || ""} onChange={(v) => setItem(i, { url: v })} />
							<TextControl label="Vị trí ảnh (object-position)" value={c.position || ""} onChange={(v) => setItem(i, { position: v })} help='Ví dụ "50% 40%"' />
						</div>
					))}
				</PanelBody>
			</InspectorControls>

			<section {...blockProps}>
				<div className="hcr__wrap">
					<div className="hcr__top">
						<h2 className="hcr__title">
							<RichText tagName="span" style={{ color: "inherit" }} value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Nhà sáng tạo" />{" "}
							<RichText tagName="span" value={titleAccent} onChange={(v) => setAttributes({ titleAccent: v })} placeholder="Welive." />
						</h2>
						<span className="hcr__link-mock">
							<RichText tagName="span" style={{ fontSize: "inherit" }} value={linkText} onChange={(v) => setAttributes({ linkText: v })} placeholder="Xem tất cả" allowedFormats={[]} /> ↗
						</span>
					</div>
					<div className="hcr__grid">
						{creators.map((c, i) => (
							<div className="hcr__card" key={i}>
								<div className="hcr__photo">
									<ImagePicker imageId={c.imageId} imageUrl={c.imageUrl} onSelect={(v) => setItem(i, v)} />
								</div>
								<RichText tagName="h3" value={c.name} onChange={(v) => setItem(i, { name: v })} placeholder="Tên" allowedFormats={[]} />
								<RichText tagName="span" value={c.cta} onChange={(v) => setItem(i, { cta: v })} placeholder="Chữ link" allowedFormats={[]} />
								<Button className="hcr__remove" isDestructive variant="link" onClick={() => setAttributes({ creators: creators.filter((_, k) => k !== i) })}>
									Xoá
								</Button>
							</div>
						))}
					</div>
					<Button variant="secondary" style={{ marginTop: 20 }} onClick={() => setAttributes({ creators: [...creators, { name: "", cta: "Xem câu chuyện", url: "", imageId: 0, imageUrl: "", position: "50% 50%" }] })}>
						+ Thêm creator
					</Button>
				</div>
			</section>
		</>
	);
}
