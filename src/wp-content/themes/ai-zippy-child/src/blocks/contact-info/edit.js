import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, SelectControl, Button } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { cards = [], textAlign } = attributes;
	const blockProps = useBlockProps({ className: `ci-editor has-text-align-${textAlign || "left"}` });

	const updateCard = (index, key, value) => {
		const newCards = [...cards];
		newCards[index] = { ...newCards[index], [key]: value };
		setAttributes({ cards: newCards });
	};

	const addCard = () => {
		const newCards = [
			...cards,
			{ title: "New Detail", content: "Details here", icon: "email" }
		];
		setAttributes({ cards: newCards });
	};

	const removeCard = (index) => {
		const newCards = cards.filter((_, i) => i !== index);
		setAttributes({ cards: newCards });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title="Text Alignment" initialOpen={false}>
					<SelectControl
						label="Text Alignment"
						value={textAlign || "left"}
						options={[
							{ label: "Left", value: "left" },
							{ label: "Center", value: "center" },
							{ label: "Right", value: "right" },
							{ label: "Justify", value: "justify" }
						]}
						onChange={(val) => setAttributes({ textAlign: val })}
					/>
				</PanelBody>
				<PanelBody title="Manage Contact Details" initialOpen={true}>
					{cards.map((card, index) => (
						<div key={index} style={{ borderBottom: "1px solid #eee", paddingBottom: 15, marginBottom: 15 }}>
							<div style={{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
								<strong>Card #{index + 1}</strong>
								<Button isDestructive variant="link" onClick={() => removeCard(index)}>
									Remove
								</Button>
							</div>
							<SelectControl
								label="Icon Type"
								value={card.icon}
								options={[
									{ label: "Email Icon", value: "email" },
									{ label: "Location Icon", value: "location" },
									{ label: "Phone Icon", value: "phone" }
								]}
								onChange={(val) => updateCard(index, "icon", val)}
							/>
							<TextControl
								label="Title"
								value={card.title}
								onChange={(val) => updateCard(index, "title", val)}
							/>
							<TextControl
								label="Content"
								value={card.content}
								onChange={(val) => updateCard(index, "content", val)}
							/>
						</div>
					))}
					<Button variant="primary" style={{ width: "100%" }} onClick={addCard}>
						+ Add Contact Card
					</Button>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps} style={{ background: "#111", padding: 20, border: "1px dashed #444", borderRadius: 8 }}>
				<h3 style={{ color: "#fff", margin: "0 0 15px 0" }}>Contact Info Cards Preview</h3>
				<div style={{ display: "grid", gridTemplateColumns: "repeat(3, 1fr)", gap: 15 }}>
					{cards.map((card, index) => (
						<div key={index} style={{ background: "#1a1a1a", border: "1px solid #333", borderRadius: 8, padding: 15 }}>
							<strong style={{ color: "#EC4600", textTransform: "uppercase", fontSize: 11 }}>{card.icon} - {card.title}</strong>
							<p style={{ color: "#fff", margin: "5px 0 0 0", fontSize: 13, wordBreak: "break-all" }}>{card.content}</p>
						</div>
					))}
				</div>
			</div>
		</>
	);
}
