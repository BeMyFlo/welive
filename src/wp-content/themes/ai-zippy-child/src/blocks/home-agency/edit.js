import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, Button } from "@wordpress/components";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { kicker, title, titleAccent, linkText, linkUrl, cards = [] } = attributes;
	const blockProps = useBlockProps({ className: "hag" });
	const setCard = (i, patch) => setAttributes({ cards: cards.map((c, k) => (k === i ? { ...c, ...patch } : c)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Link mặc định" initialOpen={true}>
					<TextControl label="Link 'Khám phá giải pháp' (và card nếu không có link riêng)" value={linkUrl} onChange={(v) => setAttributes({ linkUrl: v })} />
				</PanelBody>
				<PanelBody title="Link riêng từng card" initialOpen={false}>
					{cards.map((c, i) => (
						<TextControl key={i} label={`Card ${i + 1}`} value={c.url || ""} onChange={(v) => setCard(i, { url: v })} />
					))}
					<Button variant="secondary" onClick={() => setAttributes({ cards: [...cards, { label: "", title: "", text: "", url: "", imageId: 0, imageUrl: "" }] })}>
						+ Thêm card
					</Button>
					{cards.length > 0 && (
						<Button isDestructive variant="link" style={{ marginLeft: 12 }} onClick={() => setAttributes({ cards: cards.slice(0, -1) })}>
							Xoá card cuối
						</Button>
					)}
				</PanelBody>
			</InspectorControls>

			<section {...blockProps}>
				<div className="hag__wrap">
					<div className="hag__top">
						<div>
							<RichText tagName="p" className="hag__kicker" value={kicker} onChange={(v) => setAttributes({ kicker: v })} placeholder="Nhãn nhỏ" allowedFormats={[]} />
							<h2 className="hag__title">
								<RichText tagName="span" style={{ color: "inherit" }} value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Giải pháp" />{" "}
								<RichText tagName="span" value={titleAccent} onChange={(v) => setAttributes({ titleAccent: v })} placeholder="Agency." />
							</h2>
						</div>
						<span className="hag__link-mock">
							<RichText tagName="span" value={linkText} onChange={(v) => setAttributes({ linkText: v })} placeholder="Link" allowedFormats={[]} /> ↗︎
						</span>
					</div>
					<div className="hag__grid">
						{cards.map((c, i) => (
							<div className="hag__card" key={i}>
								<div className="hag__media">
									<ImagePicker imageId={c.imageId} imageUrl={c.imageUrl} onSelect={(v) => setCard(i, v)} />
								</div>
								<div>
									<RichText tagName="p" value={c.label} onChange={(v) => setCard(i, { label: v })} placeholder="NHÃN" allowedFormats={[]} />
									<RichText tagName="h3" value={c.title} onChange={(v) => setCard(i, { title: v })} placeholder="Tiêu đề" />
									<RichText tagName="span" value={c.text} onChange={(v) => setCard(i, { text: v })} placeholder="Mô tả" allowedFormats={[]} />
								</div>
							</div>
						))}
					</div>
				</div>
			</section>
		</>
	);
}
