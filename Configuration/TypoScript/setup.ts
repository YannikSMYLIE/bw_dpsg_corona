
# Module configuration
module.tx_bwdpsgcorona {
  view {
    templateRootPaths.0 = EXT:bw_dpsg_corona/Resources/Private/Common/Templates/
    templateRootPaths.1 = {$module.tx_bwdpsgcorona.view.templateRootPath}
    partialRootPaths.0 = EXT:bw_dpsg_corona/Resources/Private/Common/Partials/
    partialRootPaths.1 = {$module.tx_bwdpsgcorona.view.partialRootPath}
    layoutRootPaths.0 = EXT:bw_dpsg_corona/Resources/Private/Common/Layouts/
    layoutRootPaths.1 = {$module.tx_bwdpsgcorona.view.layoutRootPath}
  }
  persistence {
    storagePid = {$plugin.tx_bwdpsgcorona.persistence.storagePid}
  }
}

plugin.tx_bwdpsgcorona {
  view {
    templateRootPaths.0 = EXT:bw_dpsg_corona/Resources/Private/Common/Templates/
    templateRootPaths.1 = {$plugin.tx_bwdpsgcorona.view.templateRootPath}
    partialRootPaths.0 = EXT:bw_dpsg_corona/Resources/Private/Common/Partials/
    partialRootPaths.1 = {$plugin.tx_bwdpsgcorona.view.partialRootPath}
    layoutRootPaths.0 = EXT:bw_dpsg_corona/Resources/Private/Common/Layouts/
    layoutRootPaths.1 = {$plugin.tx_bwdpsgcorona.view.layoutRootPath}
  }
  persistence {
    storagePid = {$plugin.tx_bwdpsgcorona.persistence.storagePid}
  }
}

# Page Konfiguration
page {
  includeCSS {
    bw_corona = EXT:bw_dpsg_corona/Resources/Public/Css/style.scss
  }
}