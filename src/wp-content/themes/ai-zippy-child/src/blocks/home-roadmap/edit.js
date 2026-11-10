import { useBlockProps, RichText, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, Button, SelectControl } from "@wordpress/components";
import { useState } from "@wordpress/element";
import ImagePicker from "../shared/ImagePicker.js";

export default function Edit({ attributes, setAttributes }) {
	const { kicker, title, intro, ctaText, ctaUrl, cardCta, cards = [], layout, tabs = [], image, note } = attributes;
	const isTabs = layout === "tabs";
	const [active, setActive] = useState(0);
	const blockProps = useBlockProps({ className: isTabs ? "hrm hrm--tabs" : "hrm" });
	const photo = image || {};
	const setTab = (i, patch) => setAttributes({ tabs: tabs.map((t, k) => (k === i ? { ...t, ...patch } : t)) });
	const setBullet = (i, j, v) => setTab(i, { bullets: (tabs[i].bullets || []).map((b, k) => (k === j ? v : b)) });
	const cur = tabs[Math.min(active, Math.max(tabs.length - 1, 0))];
	const curIndex = Math.min(active, Math.max(tabs.length - 1, 0));
	const setCard = (i, patch) => setAttributes({ cards: cards.map((c, k) => (k === i ? { ...c, ...patch } : c)) });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Layout" initialOpen={true}>
					<SelectControl
						label="Layout"
						value={isTabs ? "tabs" : "cards"}
						options={[
							{ label: "Tab ngày + khung chi tiết (mới)", value: "tabs" },
							{ label: "4 card (cũ)", value: "cards" },
						]}
						onChange={(v) => setAttributes({ layout: v })}
					/>
					{isTabs && <TextControl label="Alt ảnh" value={photo.alt || ""} onChange={(v) => setAttributes({ image: { ...photo, alt: v } })} />}
				</PanelBody>
				{isTabs && (
					<PanelBody title="Ý trong khung chi tiết" initialOpen={false}>
						{tabs.map((t, i) => (
							<div key={i} style={{ marginBottom: 12 }}>
								<strong>{t.days}</strong>
								<div style={{ display: "flex", gap: 8, marginTop: 6 }}>
									<Button variant="secondary" onClick={() => setTab(i, { bullets: [...(t.bullets || []), ""] })}>+ Thêm ý</Button>
									{(t.bullets || []).length > 0 && (
										<Button isDestructive variant="link" onClick={() => setTab(i, { bullets: t.bullets.slice(0, -1) })}>Xoá ý cuối</Button>
									)}
								</div>
							</div>
						))}
					</PanelBody>
				)}
				<PanelBody title="Nút chính" initialOpen={true}>
					<TextControl label="Anchor (id để link tới, vd #lo-trinh)" value={attributes.anchor || ""} onChange={(v) => setAttributes({ anchor: v })} />
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
							<RichText tagName="span" value={ctaText} onChange={(v) => setAttributes({ ctaText: v })} placeholder="Nút" allowedFormats={[]} /> ↗︎
						</span>
					</div>
					<RichText tagName="p" className="hrm__intro" value={intro} onChange={(v) => setAttributes({ intro: v })} placeholder="Mô tả ngắn" />
					{isTabs ? (
						<>
							<div className="hrm__tabs">
								{tabs.map((t, i) => (
									<div key={i} className="hrm__tab" role="button" tabIndex={0} aria-selected={i === curIndex ? "true" : "false"} onClick={() => setActive(i)}>
										<RichText tagName="span" className="hrm__days" value={t.days} onChange={(v) => setTab(i, { days: v })} placeholder="NGÀY 1–3" allowedFormats={[]} />
										<RichText tagName="strong" value={t.label} onChange={(v) => setTab(i, { label: v })} placeholder="Tên chặng" allowedFormats={[]} />
									</div>
								))}
							</div>
							<div className="hrm__detail-box">
								{cur && (
									<div className="hrm__panel">
										<RichText tagName="h3" value={cur.panelTitle} onChange={(v) => setTab(curIndex, { panelTitle: v })} placeholder="Tiêu đề chi tiết" allowedFormats={[]} />
										<ul>
											{(cur.bullets || []).map((b, j) => (
												<li key={j}>
													<RichText tagName="span" value={b} onChange={(v) => setBullet(curIndex, j, v)} placeholder="Ý chính" allowedFormats={[]} />
												</li>
											))}
										</ul>
									</div>
								)}
								<ImagePicker className="hrm__photo" imageId={photo.imageId} imageUrl={photo.imageUrl} onSelect={(patch) => setAttributes({ image: { ...photo, ...patch } })} />
							</div>
							<RichText tagName="p" className="hrm__note" value={note} onChange={(v) => setAttributes({ note: v })} placeholder="Ghi chú nhỏ" allowedFormats={[]} />
						</>
					) : (
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
									{cardCta} <b>↗︎</b>
								</span>
							</div>
						))}
					</div>
					)}
				</div>
			</section>
		</>
	);
}
