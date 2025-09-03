// This plugin will open a window to prompt the user to enter a number, and
// it will then create that many rectangles on the screen.

// This file holds the main code for plugins. Code in this file has access to
// the *figma document* via the figma global object.
// You can access browser APIs in the <script> tag inside "ui.html" which has a
// full browser environment (See https://www.figma.com/plugin-docs/how-plugins-run).

// This shows the HTML page in "ui.html".
figma.showUI(__html__)

figma.on('selectionchange', () => {
    const selectedFrames = figma.currentPage.selection.filter(node => node.type === 'FRAME')
    figma.ui.postMessage({ type: 'frame-select', payload: selectedFrames })
})

// figma.ui.onmessage = (msg: { type: string; count: number }) => {
//     if (msg.type === 'create-shapes') {
//         // This plugin creates rectangles on the screen.
//         const numberOfRectangles = msg.count

//         const nodes: SceneNode[] = []
//         for (let i = 0; i < numberOfRectangles; i++) {
//             const rect = figma.createRectangle()
//             rect.x = i * 150
//             rect.fills = [{ type: 'SOLID', color: { r: 1, g: 0.5, b: 0 } }]
//             figma.currentPage.appendChild(rect)
//             nodes.push(rect)
//         }
//         figma.currentPage.selection = nodes
//         figma.viewport.scrollAndZoomIntoView(nodes)
//     }

//     // Make sure to close the plugin when you're done. Otherwise the plugin will
//     // keep running, which shows the cancel button at the bottom of the screen.
//     figma.closePlugin()
// }
