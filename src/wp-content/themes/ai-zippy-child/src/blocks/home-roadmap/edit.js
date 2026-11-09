import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { kicker, title, intro, ctaText, ctaUrl, cardCta, cards = [] } = attributes;
	const blockProps = useBlockProps({ className: "hrm" });
	const setCard = (i, patch) => setAttributes({ cards: cards.map((c, k) => (k === i ? { ...c, ...patch } : c)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Nút chính" initialOpen={true}>
					<TextControl label="Link (cũng là link mặc định của card)" value={ctaUrl} onChange={(v) => setAttributes({ ctaUrl: v })} />
					<TextControl label="Chữ cuối mỗi card" value={cardCta} onChange={(v) => setAttributes({ cardCta: v })} />
				</PanelBody>
				<PanelBody title="Card" initialOpen={false}>
					{cards.map((c, i) => (
						<TextControl key={i} label={`Link riêng card ${i + 1} (để trống = link mặc định)`} value={c.url || ""} onChange={(v) => setCard(i, { url: v })} />
					))}
					<Button variant="secondary" onClick={() => setAttributes({ cards: [...cards, { index: String(cards.length + 1).padStart(2, "0"), days: "", title: "", detail: "", url: "" }] })}>
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
				<div className="hrm__wrap">
					<div className="hrm__top">
						<div>
							<RichText tagName="p" className="hrm__kicker" value={kicker} onChange={(v) => setAttributes({ kicker: v })} placeholder="Nhãn nhỏ" allowedFormats={[]} />
							<RichText tagName="h2" className="hrm__title" value={title} onChange={(v) => setAttributes({ title: v })} placeholder="Tiêu đề" />
						</div>
						<span className="hrm__pill-mock">
							<RichText tagName="span" value={ctaText} onChange={(v) => setAttributes({ ctaText: v })} placeholder="Nút" allowedFormats={[]} /> ↗
						</span>
					</div>
					<RichText tagName="p" className="hrm__intro" value={intro} onChange={(v) => setAttributes({ intro: v })} placeholder="Mô tả ngắn" />
					<div className="hrm__cards">
						{cards.map((c, i) => (
							<div className="hrm__card" key={i}>
								<RichText tagName="span" className="hrm__index" value={c.index} onChange={(v) => setCard(i, { index: v })} placeholder="01" allowedFormats={[]} />
								<div>
									<RichText tagName="p" className="hrm__days" value={c.days} onChange={(v) => setCard(i, { days: v })} placeholder="NGÀY 1–3" allowedFormats={[]} />
									<RichText tagName="h3" className="hrm__card-title" value={c.title} onChange={(v) => setCard(i, { title: v })} placeholder="Tiêu đề (Shift+Enter xuống dòng)" />
									<RichText tagName="span" className="hrm__detail" value={c.detail} onChange={(v) => setCard(i, { detail: v })} placeholder="Mô tả" />
								</div>
								<span className="hrm__card-cta">
									{cardCta} <b>↗</b>
								</span>
							</div>
						))}
					</div>
				</div>
			</section>
		</>
	);
}
