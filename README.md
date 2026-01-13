# 👻 Disable Elementor Block

**Make your elements vanish from the live site, but keep them safe in the editor!**

Ever wanted to temporarily remove a section, widget/block, or container from your website without deleting it forever? Maybe you are testing a new layout, or saving a seasonal banner for next year?

*Disable Elementor Block* plugin completely stops the element from rendering on the frontend (no HTML code output), but keeps it visible (and editable) in your Elementor Editor with a clear visual indicator.

## ✨ Features

* **True Disabling:** It doesn't just hide elements with CSS (`display: none`). It stops them from loading in the DOM entirely. Great for performance and clean code!
* **Visual Editor Feedback:** Disabled elements get a cool striped background and a red "DISABLED" badge in the editor, so you never forget they are hidden.
* **Universal Support:** Works on **Sections**, **Columns**, **Containers** (Flexbox/Grid), and **Widgets** (Blocks).
* **Easy Access:** Adds a simple toggle switch right inside the Elementor "Advanced" tab.

## 🛠️ Installation

1. Download the plugin `.zip` file (or clone this repo).
2. Go to your **WordPress Dashboard** > **Plugins** > **Add New**.
3. Click **Upload Plugin** and select the file.
4. **Activate** the plugin.
5. That's it! You are ready to ghost some elements. 👻

## 🚀 How to Use

It's super simple. Follow these steps:

1. Open any page in **Elementor**.
2. Select the element you want to hide (Section, Container, Widget, etc.).
3. Go to the **Advanced** tab in the sidebar.
4. Scroll down to the **Visibility / Disabler** section.
5. Toggle **"Disable this Element?"** to **YES**.

**Poof!** 💨
* **On the Live Site:** The element is gone. Zero HTML output.
* **In the Editor:** The element turns semi-transparent with a red dashed outline, letting you know it's currently disabled.

## ❓ FAQ

**Q: Can I still edit the element while it's disabled?**
A: **Yes!** Unlike other solutions, we keep the element visible in the backend so you can tweak settings, change text, or update styles without turning it back on first.

**Q: Is this different from Elementor's "Responsive" hide feature?**
A: **Yes.** Elementor's native responsive hiding uses CSS (`display: none`), meaning the code is still loaded in the browser (just hidden). **This plugin** prevents the server from generating the HTML code at all.

## 🤝 Contributing

Found a bug? Have a cool idea? Feel free to open an issue or submit a pull request!

## 👤 Author

Enes Sönmez ([enes.dev](https://enes.dev))

* **X:** [@enes_dev](https://x.com/enes_dev)
* **GitHub:** [@eness](https://github.com/eness)

## 📄 License

This project is licensed under the **GNU General Public License v3.0**.
See the [GPL-3.0 License](https://www.gnu.org/licenses/gpl-3.0.en.html) page for details.