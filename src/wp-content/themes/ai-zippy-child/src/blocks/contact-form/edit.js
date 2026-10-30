import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, TextareaControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { title, mapIframe } = attributes;
	const blockProps = useBlockProps({ className: "cf-editor" });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Contact Form Settings" initialOpen={true}>
					<TextControl
						label="Form Title"
						value={title}
						onChange={(val) => setAttributes({ title: val })}
					/>
					<TextareaControl
						label="Google Maps Iframe HTML"
						value={mapIframe}
						onChange={(val) => setAttributes({ mapIframe: val })}
						help="Copy & paste the iframe embed HTML from Google Maps."
						rows={8}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps} style={{ background: "#111", padding: 25, border: "1px dashed #444", borderRadius: 8 }}>
				<h3 style={{ color: "#fff", margin: "0 0 10px 0" }}>{title || "Inquiry Form"} Preview</h3>
				<div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 20 }}>
					<div style={{ padding: 15, border: "1px solid #333", background: "#1a1a1a", borderRadius: 8 }}>
						<label style={{ display: "block", color: "#888", marginBottom: 5, fontSize: 11 }}>Form Inputs Preview</label>
						<div style={{ background: "#222", height: 35, borderRadius: 4, marginBottom: 10, padding: 8, color: "#555", fontSize: 12 }}>Name input</div>
						<div style={{ background: "#222", height: 35, borderRadius: 4, marginBottom: 10, padding: 8, color: "#555", fontSize: 12 }}>Email input</div>
						<div style={{ background: "#222", height: 35, borderRadius: 4, marginBottom: 10, padding: 8, color: "#555", fontSize: 12 }}>Phone input</div>
						<div style={{ background: "#222", height: 80, borderRadius: 4, marginBottom: 10, padding: 8, color: "#555", fontSize: 12 }}>Message input</div>
						<div style={{ background: "#EC4600", height: 40, borderRadius: 4, display: "flex", alignItems: "center", justifyContent: "center", color: "#fff", fontWeight: "bold" }}>SUBMIT</div>
					</div>
					<div>
						<label style={{ display: "block", color: "#888", marginBottom: 5, fontSize: 11 }}>Google Map Iframe Preview</label>
						{mapIframe ? (
							<div
								style={{ width: "100%", height: 260, border: "1px solid #333", overflow: "hidden", borderRadius: 8 }}
								dangerouslySetInnerHTML={{ __html: mapIframe }}
							/>
						) : (
							<div style={{ height: 260, border: "1px dashed #444", borderRadius: 8, display: "flex", alignItems: "center", justifyContent: "center", color: "#666" }}>
								No map iframe configured
							</div>
						)}
					</div>
				</div>
			</div>
		</>
	);
}
