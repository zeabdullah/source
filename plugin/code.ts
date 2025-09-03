figma.showUI(__html__)

figma.on('selectionchange', emitFramesSelection)

function emitFramesSelection() {
    const selectedFrames = figma.currentPage.selection.filter(node => node.type === 'FRAME')
    figma.ui.postMessage({ type: 'frame-select', payload: selectedFrames })
}
