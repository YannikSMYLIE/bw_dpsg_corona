
module.tx_bwdpsgcorona {
  view {
    # cat=module.tx_bwdpsgcorona/file; type=string; label=Path to template root (BE)
    templateRootPath = EXT:bw_dpsg_corona/Resources/Private/Backend/Templates/
    # cat=module.tx_bwdpsgcorona/file; type=string; label=Path to template partials (BE)
    partialRootPath = EXT:bw_dpsg_corona/Resources/Private/Backend/Partials/
    # cat=module.tx_bwdpsgcorona/file; type=string; label=Path to template layouts (BE)
    layoutRootPath = EXT:bw_dpsg_corona/Resources/Private/Backend/Layouts/
  }
  persistence {
    # cat=module.tx_bwdpsgcorona//a; type=string; label=Default storage PID
    storagePid =
  }
}

plugin.tx_bwdpsgcorona {
  view {
    # cat=plugin.tx_bwdpsgcorona/file; type=string; label=Path to template root (FE)
    templateRootPath = EXT:bw_dpsg_corona/Resources/Private/Frontend/Templates/
    # cat=plugin.tx_bwdpsgcorona/file; type=string; label=Path to template partials (FE)
    partialRootPath = EXT:bw_dpsg_corona/Resources/Private/Frontend/Partials/
    # cat=plugin.tx_bwdpsgcorona/file; type=string; label=Path to template layouts (FE)
    layoutRootPath = EXT:bw_dpsg_corona/Resources/Private/Frontend/Layouts/
  }
  persistence {
    # cat=plugin.tx_bwdpsgcorona//a; type=string; label=Default storage PID
    storagePid =
  }
}